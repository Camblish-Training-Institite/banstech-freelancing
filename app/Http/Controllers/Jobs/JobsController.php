<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use App\Models\Contest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $jobs = Job::where('user_id', '!=', $user_id)
        ->where('status', 'open') // Fetch all open jobs except those created by the user
        ->paginate(10);
        $contests = Contest::where('client_id', '!=', $user_id)->paginate(10);

        // dd($jobs);
        return view('Users.Freelancers.layouts.body.job-listing', ['jobs' => $jobs, 'contests' => $contests]);
    }

    //This is for showing single job
    public function show(int $id){
        $job = Job::findOrFail($id);

        // dd($job);
        return view('Users.Clients.jobs.job-show', ['job' => $job]);

    }

    //This is for showing single job
    public function show_freelancer(int $id){
        $job = Job::findOrFail($id);

        // dd($job);
        return view('Users.Freelancers.jobs.job-show', ['job' => $job]);

    }

    //This shows the job form on views 
    public function create(){

        return view('client.createJobs');
    }

    //This stores the instered data to the database (Job table using Job module)
    public function store(Request $request){

        //converting comma-separated string to array before validation
        $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'nullable|date',
        'budget' => 'required|numeric',
        'status' => 'required|string|in:open,in_progress,assigned,completed,cancelled',
        'skills' => 'string',
        ]);

        $req_skills = $request->input('skills');
        $skills = is_array($req_skills) ? implode(',', $req_skills) : $req_skills;

        Job::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description'=> $request->description,
        'deadline' => $request->deadline,
        'budget' => $request->budget,
        'status' => $request->status,
        'skills' => $skills,
        
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