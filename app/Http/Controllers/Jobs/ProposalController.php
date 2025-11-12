<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proposal;
use App\Models\Job;
use App\Notifications\NewProposalNotification;
use Illuminate\Support\Facades\Auth;
use App\Models\Contract;
use App\Notifications\ProposalAcceptedNotification;

class ProposalController extends Controller
{

    public function index_client(){
        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view proposals.');
        }
        $jobs = Job::where('user_id', Auth::id())
        ->where('status', 'open') // Fetch all open jobs for the client
        ->get(); // Fetch all jobs for the client
        // dd($jobs);

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

        $proposal = Proposal::findOrFail($id); // Fetch the proposal by ID

        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to view your proposals.');
        }
        // dd($proposal->user_id . '-' . Auth::user()->id);
        
        if(!Auth::user()->id == $proposal->user_id){
            return redirect()->back()->with('error', 'You do not have permission to view this proposal.');
        }

        $notification = Auth::user()->notifications()->where('data->proposal_id', $proposal->id)->first();
        $notification?->markAsRead();

        

        return view('Users.Freelancers.proposals.view-proposal', ['proposal' => $proposal]);
    }

    public function job_show(int $job_id){
        $job = Job::findOrFail($job_id); // Fetch the job by ID
        return view('Users.Clients.layouts.proposal-section', ['job' => $job]);
    }

    public function show(Proposal $proposal){
        $user = Auth::user(); // Get the authenticated user's ID
        $notification = $user->notifications()->where('data->proposal_id', $proposal->id)->first();

        if($notification && is_null($notification->read_at)){
            $notification->markAsRead();
        }

        $proposal = proposal::findOrFail($proposal->id); // Fetch the job by ID
        return view('Users.Clients.proposals.view-proposal', ['proposal' => $proposal]);
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

        $proposal = Proposal::latest()->first(); // Get the most recently created proposal
        $job = $proposal->job;
        $client = $job->user; // Assumes a 'client' relationship on the Job model

        $client->notify(new NewProposalNotification($proposal));

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

    public function acceptProposal(Proposal $proposal){
        $user_id = Auth::user()->id; // Get the authenticated user's ID

        if (!$proposal || !$proposal->job || !$proposal->user) {
            return redirect()->back()->with('error', 'Invalid proposal data.');
        }
        
        if($proposal->job->status == "assigned" || $proposal->job->status == "in_progress"){
            return redirect()->back()->with('error', 'cannot accept freelancer to a project that is already assigned');
        }

        $proposal->status = 'accepted'; // Update the proposal status to accepted
        $proposal->save();

        $proposal->job->proposals()
        ->where('id', '!=', $proposal->id)
        ->where('status', '!=', 'accepted') // Optional: avoid updating if already accepted
        ->update(['status' => 'rejected']);

        $job = $proposal->job; // Get the job associated with the proposal
        $job->status = 'assigned'; // Update the job status to in progress
        $job->save();

        Contract::create([
            'job_id' => $job->id,
            'user_id' => $proposal->user_id,
            'agreed_amount' => $proposal->bid_amount,
            'status' => 'active', // Set the initial status of the contract
            'end_date' => $job->deadline,
        ]);

        $freelancer = $proposal->user; // Assumes a 'user' relationship on the Proposal model
        $client = $proposal->job->user;

        $freelancer->notify(new ProposalAcceptedNotification($proposal->id));
        if(!$proposal->job->job_funded){
            $client->notify(new ProposalAcceptedNotification($proposal->id));
        }

        return redirect()->route('billing')->with('success', 'Proposal rejected successfully.');
        
    }

    public function rejectProposal(Proposal $proposal){
        $proposal->status = 'rejected'; // Update the proposal status to accepted
        $proposal->save();

        if (!$proposal || !$proposal->job || !$proposal->user) {
            return redirect()->back()->with('error', 'Invalid proposal data.');
        }

        $freelancer = $proposal->user; // Assumes a 'user' relationship on the Proposal model
        // dd($freelancer);
        $freelancer->notify(new ProposalAcceptedNotification($proposal->id));

        return redirect()->route('client.proposals.list')->with('success', 'Proposal rejected successfully.');
    }

    public function withdrawProposal(Proposal $proposal){

        if(!Auth::check()){
            return redirect()->route('login')->with('error', 'You must be logged in to withdraw a proposal.');
        }

        $proposal->save();
        return redirect()->route('freelancer.proposals.index')->with('success', 'Proposal withdrawn successfully.');
    }

    public function destroy(int $id){
        // This method will handle cancelling a proposal
        // Logic to update the proposal status to cancelled goes here   
    }

}
