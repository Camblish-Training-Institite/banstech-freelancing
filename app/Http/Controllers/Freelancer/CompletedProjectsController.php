<?php

namespace App\Http\Controllers\Freelancer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompletedProjectsController extends Controller
{
    public function completedProjects()
    {
        // Fetch completed projects from database
        // $projects = auth()->user()->completedProjects()->where('status', 'completed')->get();

        return view('dashboards.freelancer.completed-projects');
    }
}
