<?php

namespace App\Http\Controllers\Jobs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;

class JobMapController extends Controller
{
    /**
     * Show the full-screen map or the mini-map data
     */
    public function show(Job $job)
    {
        // Ensure the job has location data
        if (!$job->location) {
            return back()->with('error', 'This job does not have a physical location.');
        }

        return view('geo_location.main_map', compact('job'));
    }
}
