<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function index_client(){
        if(!Auth::check()){

        }

        $user = Auth::user();
        $clientId = Auth::user()->id;
        $projects = $user->contracts()->paginate(10);

        // dd($projects);

        return view('Users.Clients.layouts.project-section', ['projects' => $projects]);
    }

    public function index(){
        if(!Auth::check()){

        }

        $freelancerId = Auth::user()->id;
        $projects = Contract::where('user_id', $freelancerId)->get();

        return view('Users.Freelancers.layouts.job-section', ['projects' => $projects]);
    }

    public function show_client(int $id){
        $contract = Contract::find($id);
        if ($contract) {
            return view('Users.Clients.projects.view', ['project' => $contract]);
        }
        return redirect()->back()->with('error', 'Contract not found.');
    }

    public function show(int $id){
        $contract = Contract::find($id);
        if ($contract) {
            return view('Users.Freelancers.projects.view', ['project' => $contract]);
        }
        return redirect()->back()->with('error', 'Contract not found.');
    }

    public function requestMilestone(Contract $project)
    {
        // Return the view for requesting a milestone
        return view('Users.Freelancers.projects.request-milestone', ['project' => $project]);

    }

    public function create() {

    }

    public function store(Request $request){

    }

    public function edit(int $id){

    }

    public function update(Request $request, int $id){

    }

    public function cancelContract(Request $request, int $id){
        $contract = Contract::find($id);
        if ($contract && $contract->job->user->user_id == Auth::id()) {
            $contract->status = 'cancelled';
            $contract->save();
            return redirect()->back()->with('success', 'Contract cancelled successfully.');
        }
        return redirect()->back()->with('error', 'Contract not found or you do not have permission to cancel it.');
    }

    public function completeContract(Request $request, int $id){
        $contract = Contract::find($id);
        if ($contract && $contract->job->user->user_id == Auth::id()) {
            $contract->status = 'completed';
            $contract->save();
            return redirect()->back()->with('success', 'Contract completed successfully.');
        }
        return redirect()->back()->with('error', 'Contract not found or you do not have permission to complete it.');
    }
}
