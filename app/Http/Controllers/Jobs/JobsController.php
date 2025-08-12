<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    //This is for listing jobs - LISTING PAGE

    public function index_client()
    {
        if(!Auth::check()){

        }

        $clientId = Auth::user()->id;
        $jobs = Job::where('user_id', $clientId)->paginate(10);

        
        return view('Users.clients.layouts.job-section', ['jobs' => $jobs]);
    }

    public function index()
    {
        $jobs = Job::latest()->paginate(10);

        // dd($jobs);
        return view('Users.Freelancers.layouts.body.job-listing', ['jobs' => $jobs]);
    }

    //This is for showing single job
    public function show(int $id){
        $job = Job::findOrFail($id);

        // dd($job);
        return view('Users.Clients.layouts.body.job-show', ['job' => $job]);

    }

    //This is for showing single job
    public function show_freelancer(int $id){
        $job = Job::findOrFail($id);

        // dd($job);
        return view('Users.Freelancers.layouts.body.job-show', ['job' => $job]);

    }

    //This shows the job form on views 
    public function create(){

        return view('client.createJobs');
    }

    //This stores the instered data to the database (Job table using Job module)
    public function store(Request $request){

        //converting comma-separated string to array before validation
        $request->merge([
            'skills' => array_map('trim',explode(',', $request->input('skills')))
        ]);

        $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'deadline' => 'nullable|date',
        'budget' => 'required|numeric',
        'status' => 'required|string|in:open,in_progress,assigned,completed,cancelled',
        'skills' => 'nullable|array',
        'skills.*' => 'string',
        ]);

        Job::create([
        'user_id' => auth()->id(),
        'title' => $request->title,
        'description'=> $request->description,
        'deadline' => $request->deadline,
        'budget' => $request->budget,
        'status' => $request->status,
        'skills' => json_encode($request->skills),
        
        ]);

    return redirect()->route('jobs.index')->with('success','Job added!');
    
}

//This shall view the Job form to edit fetch by id
    public function edit(Job $job){
      
        return view('client.edit',compact('job'));  
    }

//after a user has edited shall click updated button-> which is this controller
    public function update(Request $request, Job $job) {
         
 //converting comma-separated string to array before validation

        // dd($request->input('skills'));
        $request->merge([
            'skills' => array_map('trim', explode(',', $request->input('skills')))
        ]);

        // dd($request->input('skills'));   

        $request->validate([ 
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'nullable|date',
            'budget' => 'required|numeric',
            'status' => 'required|string|in:open,in_progress,assigned,completed,cancelled',
            'skills' => 'nullable|array',
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

            return redirect()->route('client.jobs.index')->with('success','Successfully updated!');
    }

    //This is for deleting, using the method destroy
    public function destroy(Job $job){
       
        $job->delete();
        return redirect()->route('jobs.index')->with('success','Deleted successful!');
    }

}