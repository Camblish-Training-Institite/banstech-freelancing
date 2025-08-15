<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class ProposalController extends Controller
{

    public function index_client(){
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view proposals.');
        }
        $jobs = Job::where('user_id', Auth::id())->get(); // Fetch all jobs for the client

        // dd($jobs->proposals);

        return view('Users.Clients.layouts.proposal-section', ['jobs' => $jobs]);
    }

    public function index(){
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view proposals.');
        }
        $id = Auth::user()->id; // Get the authenticated user's ID
        $proposals = Proposal::where('user_id', $id)->get();    // Fetch all proposals
        // dd($proposals);

        return view('Users.Freelancers.layouts.proposal-section', ['proposals' => $proposals]);

    }

    public function myProposal(int $id){
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view your proposals.');
        }
        $proposal = Proposal::findOrFail($id); // Fetch all proposals for the user
        if(!Auth::user()->id == $proposal->user_id){
            return redirect()->back()->with('error', 'You do not have permission to view this proposal.');
        }

        // dd($proposal->job->skills);

        return view('Users.Freelancers.proposals.view-proposal', ['proposal' => $proposal]);
    }

    public function show(Job $job){
        $proposal = Proposal::findOrFail($job->id); // Fetch the proposal by ID

        return view('Users.Clients.proposals.show', ['proposal' => $proposal]);
    }

    public function create(int $job_id) {
        $job = Job::findOrFail($job_id); // Fetch the job by ID

        return view('Users.Freelancers.proposals.form', ['job' => $job, 'actionUrl' => route('freelancer.proposals.store'), 'method' => 'POST']);
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'job_id' => 'required|exists:jobs,id',
            'bid_amount' => 'required|numeric|min:0',
            'cover_letter' => 'required|string',
        ]);

        Proposal::create([
            'user_id' => Auth::id(), // Get the authenticated user's ID
            'job_id' => $validatedData['job_id'],
            'bid_amount' => $validatedData['bid_amount'],
            'cover_letter' => $validatedData['cover_letter'],
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('freelancer.proposals.index')->with('success', 'Proposal submitted successfully.');
    }

    public function edit(int $id) {
        $proposal = Proposal::findOrFail($id); // Fetch the job by ID
        $job = Job::findOrFail($proposal->job->id); // Fetch the job by ID

        // dd($job);

        return view('Users.Freelancers.proposals.form', ['proposal' => $proposal, 'job' => $job, 'actionUrl' => route('freelancer.proposals.update', $proposal->id), 'method' => 'PATCH']);
    }

    public function update(Request $request, int $id){

        $validatedData = $request->validate([
            'bid_amount' => 'required|numeric|min:0',
            'cover_letter' => 'required|string',
        ]);

        $proposal = Proposal::findOrFail($id); // Fetch the proposal by ID
        $proposal->bid_amount = $validatedData['bid_amount'];
        $proposal->cover_letter = $validatedData['cover_letter'];

        $proposal->save();

        return redirect()->route('freelancer.proposals.index')->with('success', 'Proposal updated successfully.');
    }

    public function acceptProposal(Request $request, int $id){
        // This method will handle accepting a proposal
        // Logic to update the proposal status to accepted goes here
    }

    public function rejectProposal(Request $request, int $id){
        // This method will handle rejecting a proposal
        // Logic to update the proposal status to rejected goes here
    }

    public function withdrawProposal(Request $request, int $id){
        // This method will handle withdrawing a proposal
        // Logic to update the proposal status to withdrawn goes here
    }

    public function destroy(int $id){
        // This method will handle cancelling a proposal
        // Logic to update the proposal status to cancelled goes here   
    }

}
