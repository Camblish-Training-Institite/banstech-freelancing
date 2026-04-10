<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Notifications\AddFundsToJob;
use Geocoder\Laravel\Facades\Geocoder;
use MatanYadaev\EloquentSpatial\Objects\Point;

class JobsController extends Controller
{
    //This is for listing jobs - LISTING PAGE

    public function index_client()
    {
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view jobs.');
        }

        $clientId = Auth::user()->id;
        $jobs = Job::where('user_id', $clientId)->paginate(10);

        // dd($jobs);

        
        return view('Users.clients.layouts.job-section', ['jobs' => $jobs]);
    }

    public function index()
    {
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view jobs.');
        }

        $user_id = Auth::user()->id; // Get the authenticated user's ID

        $jobs = Job::with('proposals')->where('user_id', '!=', $user_id)
        ->where('status', 'open') // Fetch all open jobs except those created by the user
        ->paginate(10);

        $contests = Contest::where('client_id', '!=', $user_id)->paginate(10);
        $type = request()->query('type');
        $query = Job::query();

        if ($type=='physical'){
            $query->physical();
        } elseif ($type=='online'){
            $query->online();
        }

        if (request()->wantsJson()){
            return response()->json($query->paginate(10));
        }



        // dd($jobs);
        return view('Users.Freelancers.layouts.body.job-listing', ['jobs' => $jobs, 'contests' => $contests]);
    }

    public function getNearbyJobs(Request $request)
    {
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 10); // Default radius is 10 km if not provided

        // Validate inputs
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid input parameters'], 400);
        }

        // Haversine formula to calculate distance
        $haversine = "(6371 * acos(cos(radians(?)) * cos(radians(ST_Y(location))) * cos(radians(ST_X(location)) - radians(?)) + sin(radians(?)) * sin(radians(ST_Y(location)))))";

        $jobs = Job::select('*')
            ->join('job_geolocations', 'jobs.id', '=', 'job_geolocations.job_id')
            ->whereRaw("$haversine < ?", [$latitude, $longitude, $latitude, $radius])
            ->with('geolocation') // Eager load geolocation relationship
            ->get();

        return response()->json($jobs);
    }

    //This is for showing single job
    public function show(int $id){
        $job = Job::findOrFail($id);
        $user = $job->user;
        // dd($job);
        return view('Users.Clients.jobs.job-show', ['job' => $job, 'user' => $user]);

    }

    //This is for showing single job
    public function show_freelancer(int $id){
        $job = Job::findOrFail($id);
        $user = $job->user;
        // dd($job);
        return view('Users.Freelancers.jobs.job-show', ['job' => $job, 'user' => $user]);

    }

    //This shows the job form on views 
    public function create(){
        $subCategories = null;
        // dd($subCategories->count());
        return view('client.createJobs', ['subCategories' => $subCategories]);
    }

    //This stores the instered data to the database (Job table using Job module)
    public function store(Request $request){

        // dd($request);

        //converting comma-separated string to array before validation
        $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'nullable|date',
        'budget' => 'required|numeric',
        'status' => 'required|string|in:open,in_progress,assigned,completed,cancelled',
        'skills' => 'string',
        'job_type' => 'string',
        'job_type' => 'string|in:online,physical',
        'job_address' => 'nullable|string',
        'radius' => 'nullable|numeric',
        ]);

        if($request->job_type == 'physical' && $request->has('job_address')){

            //convert address to geo location
            $results = Geocoder::geocode($request->job_address)->get();
            // dd($results, $request->job_address);

            if ($results->isEmpty()) {
                return back()->withErrors(['job_address' => 'Could not find this address.']);
            }

            // 2. Get the first result (best match)
            $coordinates = $results->first()->getCoordinates();
            
            // 3. Create the spatial Point
            $point = new Point($coordinates->getLatitude(), $coordinates->getLongitude());

        } 

        

        $req_skills = $request->input('skills');
        $skills = is_array($req_skills) ? implode(',', $req_skills) : $req_skills;

        Job::create([

            'user_id' => auth()->id(),
            'title' => $request->title,
            'description'=> $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'skills' => $skills,
            'job_type' => $request->job_type,
            'freelancer_radius' => $request->radius ?? null,
            'location' => $point ?? null,
        ]);

    return redirect()->route('client.jobs.list')->with('success','Job added!');
    
}

//This shall view the Job form to edit fetch by id
    public function edit(Job $job){
      
        return view('client.edit',compact('job'));  
    }

//after a user has edited shall click updated button-> which is this controller
    public function update(Request $request, Job $job) {
         
 //converting comma-separated string to array before validation

        // dd($request->input('skills'));   

        $request->validate([ 
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
            'budget' => 'required|numeric',
            'status' => 'required|string|in:open,in_progress,assigned,completed,cancelled',
            'skills.*' => 'string',
            ]);
            
            
            $job->update([               
            'title' => $request->title,
            'description'=> $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => $request->status,
            'skills' => $request->skills,
                
        ]);

        return redirect()->route('client.jobs.list')->with('success','Successfully updated!');
    }

    //This is for deleting, using the method destroy
    public function destroy(Job $job){
       
        $job->delete();
        return redirect()->route('client.jobs.list')->with('success','Deleted successful!');
    }

    // private function authorizeOwner(Job $job)
    // {
    //     if ($job->client_id !== auth()->id()) {
    //         abort(403);
    //     }
    // }

}