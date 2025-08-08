<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    //This is for listing jobs - LISTING PAGE
    public function index()
    {
          $jobs = Job::latest()->paginate(10);
          return view('client.index', compact('jobs'));
    }

    //This is for showing single job
    public function show(Job $job){

        return view('client.show', compact('job'));

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
           
           
            $job->update([               
            'title' => $request->title,
            'description'=> $request->description,
            'deadline' => $request->deadline,
            'budget' => $request->budget,
            'status' => $request->status,
            'skills' => $request->skills,
             
            ]);

            return redirect()->route('jobs.index')->with('success','Successfully updated!');
    }

    //This is for deleting, using the method destroy
    public function destroy(Job $job){
       
        $job->delete();
        return redirect()->route('jobs.index')->with('success','Deleted successful!');
    }

}