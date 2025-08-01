<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function proposals()
    {
        // Fetch proposals from database
        // $proposals = auth()->user()->appliedJobs()->where('status', 'active')->get();

        return view('dashboards.freelancer.proposals');
    }
}
