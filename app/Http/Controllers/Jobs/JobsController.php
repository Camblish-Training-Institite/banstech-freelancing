<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    public function index()
    {
        // Logic to display a list of jobs
    }
    
    public function create(){
        $job = new Job();
        return view('client.project_form', ['job' => $job, 'actionURL' => route('job.store'), 'method' => 'POST']);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'nullable|numeric|min:0',
            'deadline' => 'nullable|date',
        ]);

        if(Auth::check()){
            $user_id = auth()->id();
        }
        else{
            return redirect()->back()->withErrors(['error' => 'You must be logged in to create a job.']);
        }

        Job::create($validatedData + ['user_id' => $user_id]);

        return redirect()->route('dashboard')->with('success', 'Job created successfully.');
    }

    public function edit(int $id){
        return view('client.project_form', ['actionURL' => route('job.update')]);
    }
}