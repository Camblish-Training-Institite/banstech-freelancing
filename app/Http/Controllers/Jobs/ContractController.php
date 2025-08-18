<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index(){
        if(!Auth::check()){

        }

        $freelancerId = Auth::user()->id;
        $projects = Contract::where('user_id', $freelancerId)->get();

        return view('Users.Freelancers.layouts.job-section', ['projects' => $projects]);
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
