<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JobController extends Controller
{
    public function activeJobs()
    {
        // Fetch active jobs from database
        // $jobs = auth()->user()->appliedJobs()->where('status', 'active')->get();

        return view('dashboards.freelancer.active-jobs');
    }
}
