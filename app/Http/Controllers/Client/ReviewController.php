<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\models\Contract;
use App\Models\User;
use App\Models\Job;

class ReviewController extends Controller
{
    public function create(Contract $project)
    {
        // dd($project );
        $freelancer = User::find($project->user_id);
        $job = Job::find($project->job_id);
        return view('Users.Clients.pages.review_form', compact('project', 'freelancer', 'job'));
    }

    public function store(Request $request)
    {

        // dd($request);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'contract_id' => 'required|exists:contracts,id',
            'freelancer_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $project = Contract::find($validatedData['contract_id']);

        // Create a new review record in the database
        // Assuming you have a Review model set up
        Review::create([
            'contract_id' => $project->id,
            'job_id' => $project->job_id,
            'user_id' => $validatedData['freelancer_id'],
            'rating' => $validatedData['rating'],
            'comment' => $validatedData['comment'],
        ]);

        // Redirect back with a success message
        return redirect()->route('client.projects.list')->with('success', 'Review submitted successfully.');
    }


    public function pmCreate(Contract $project)
    {
        // dd($project );
        $projectManager = User::find($project->project_manager_id);
        $job = Job::find($project->job_id);
        return view('Users.Clients.pages.review_form', compact('project', 'projectManager', 'job'));
    }
}
