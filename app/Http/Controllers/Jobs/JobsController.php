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
use Illuminate\Validation\ValidationException;

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
        $search = trim((string) request()->query('search'));
        $type = request()->query('type');
        $categoryId = request()->query('category_id');
        $funded = request()->query('funded');

        $jobsQuery = Job::with(['proposals', 'user.profile', 'category'])
            ->where('user_id', '!=', $user_id)
            ->where('status', 'open');

        if ($search !== '') {
            $jobsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if (in_array($type, ['physical', 'online'], true)) {
            $jobsQuery->where('job_type', $type);
        }

        if (filled($categoryId)) {
            $jobsQuery->where('category_id', $categoryId);
        }

        if ($funded === 'funded') {
            $jobsQuery->where('job_funded', true);
        } elseif ($funded === 'not_funded') {
            $jobsQuery->where('job_funded', false);
        }

        $jobs = $jobsQuery->latest()->paginate(10)->withQueryString();

        $contests = Contest::where('client_id', '!=', $user_id)->paginate(10);
        $categories = Category::orderBy('name')->get();

        if (request()->wantsJson()){
            return response()->json($jobs);
        }

        return view('Users.Freelancers.layouts.body.job-listing', [
            'jobs' => $jobs,
            'contests' => $contests,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'type' => $type,
                'category_id' => $categoryId,
                'funded' => $funded,
            ],
        ]);
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
        $job = Job::with([
            'user.profile',
            'user.bankDetail',
            'proposals.user.profile',
            'proposals.user.reviews',
            'contract.escrowFunding',
        ])->findOrFail($id);
        $user = $job->user;
        // dd($job);
        return view('Users.Freelancers.jobs.job-show', ['job' => $job, 'user' => $user]);

    }

    public function savedJobs()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to view saved jobs.');
        }

        $savedJobIds = collect(Auth::user()->saved_jobs ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values()
            ->all();

        $jobs = Job::with(['proposals', 'user.profile'])
            ->whereIn('id', $savedJobIds)
            ->latest()
            ->paginate(10);

        return view('Users.Freelancers.jobs.saved', compact('jobs'));
    }

    public function toggleSavedJob(Job $job)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'You must be logged in to save jobs.');
        }

        $user = Auth::user();

        if ($user->hasSavedJob($job->id)) {
            $user->unsaveJob($job->id);

            return back()->with('status', 'Job removed from your saved jobs.');
        }

        $user->saveJob($job->id);

        return back()->with('status', 'Job saved successfully.');
    }

    //This shows the job form on views 
    public function create(){
        $categories = Category::orderBy('name')->get();
        $subCategories = collect();

        return view('Users.Clients.jobs.form', [
            'categories' => $categories,
            'subCategories' => $subCategories,
        ]);
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
        'status' => 'nullable|string|in:open,in_progress,assigned,completed,cancelled',
        'skills' => 'nullable|string',
        'job_type' => 'string|in:online,physical',
        'job_address' => 'nullable|string',
        'freelancer_radius' => 'nullable|numeric',
        'mainCategory_id' => 'nullable|exists:categories,id',
        'subCategory' => 'nullable|exists:sub_categories,id',
        'custom_category' => 'nullable|string|max:255',
        'custom_subcategory' => 'nullable|string|max:255',
        ]);

        [$category, $subcategory] = $this->resolveCategorySelection($request);

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
            'status' => $request->status ?? 'open',
            'category_id' => $category?->id,
            'subcategory_id' => $subcategory?->id,
            'skills' => $skills,
            'job_type' => $request->job_type,
            'freelancer_radius' => $request->freelancer_radius ?? null,
            'location' => $point ?? null,
        ]);

    return redirect()->route('client.jobs.list')->with('success','Job added!');
    
}

//This shall view the Job form to edit fetch by id
    public function edit(Job $job){
        $categories = Category::orderBy('name')->get();
        $subCategories = $job->category_id
            ? SubCategory::where('parent_id', $job->category_id)->orderBy('name')->get()
            : collect();

        return view('Users.Clients.jobs.form', compact('job', 'categories', 'subCategories'));
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
            'status' => 'nullable|string|in:open,in_progress,assigned,completed,cancelled',
            'skills' => 'nullable|string',
            'job_type' => 'required|in:online,physical',
            'job_address' => 'nullable|string',
            'freelancer_radius' => 'nullable|numeric',
            'mainCategory_id' => 'nullable|exists:categories,id',
            'subCategory' => 'nullable|exists:sub_categories,id',
            'custom_category' => 'nullable|string|max:255',
            'custom_subcategory' => 'nullable|string|max:255',
            ]);

        [$category, $subcategory] = $this->resolveCategorySelection($request);

        $point = $job->location;

        if ($request->job_type === 'physical' && $request->filled('job_address')) {
            $results = Geocoder::geocode($request->job_address)->get();

            if ($results->isEmpty()) {
                return back()->withErrors(['job_address' => 'Could not find this address.'])->withInput();
            }

            $coordinates = $results->first()->getCoordinates();
            $point = new Point($coordinates->getLatitude(), $coordinates->getLongitude());
        } elseif ($request->job_type === 'online') {
            $point = null;
        }
            
            
            $job->update([               
            'title' => $request->title,
            'description'=> $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => $request->status ?? $job->status,
            'skills' => $request->skills,
            'job_type' => $request->job_type,
            'freelancer_radius' => $request->freelancer_radius ?? null,
            'category_id' => $category?->id,
            'subcategory_id' => $subcategory?->id,
            'location' => $point,
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

    private function resolveCategorySelection(Request $request): array
    {
        $selectedCategoryId = $request->input('mainCategory_id');
        $selectedSubcategoryId = $request->input('subCategory');
        $customCategoryName = trim((string) $request->input('custom_category'));
        $customSubcategoryName = trim((string) $request->input('custom_subcategory'));

        if (!$selectedCategoryId && $customCategoryName === '') {
            throw ValidationException::withMessages([
                'mainCategory_id' => 'Please select a category or enter a new one.',
            ]);
        }

        $category = null;

        if ($customCategoryName !== '') {
            $category = Category::firstOrCreate([
                'name' => $customCategoryName,
            ]);
        } elseif ($selectedCategoryId) {
            $category = Category::find($selectedCategoryId);
        }

        $subcategory = null;

        if ($category && $customSubcategoryName !== '') {
            $subcategory = SubCategory::firstOrCreate([
                'name' => $customSubcategoryName,
                'parent_id' => $category->id,
            ]);
        } elseif ($category && $selectedSubcategoryId) {
            $subcategory = SubCategory::where('id', $selectedSubcategoryId)
                ->where('parent_id', $category->id)
                ->first();

            if (!$subcategory) {
                throw ValidationException::withMessages([
                    'subCategory' => 'The selected subcategory does not belong to the chosen category.',
                ]);
            }
        }

        return [$category, $subcategory];
    }

}
