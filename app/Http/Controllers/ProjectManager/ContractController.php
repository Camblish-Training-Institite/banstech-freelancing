<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;

class ContractController extends Controller
{
    public function index(){
        if(!Auth::check()){

        }

        $user = Auth::user();
        $clientId = Auth::user()->id;
        $projects = $user->contracts()->paginate(10);

        // dd($projects);

        return view('Users.Clients.layouts.project-section', ['projects' => $projects]);
    }

    public function show(int $id){
        $contract = Contract::find($id);
        if ($contract) {
            return view('project-manager.projects.view', ['project' => $contract]);
        }
        return redirect()->back()->with('error', 'Contract not found.');
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
