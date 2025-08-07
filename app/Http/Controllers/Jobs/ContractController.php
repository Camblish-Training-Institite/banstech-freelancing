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

    }
}
