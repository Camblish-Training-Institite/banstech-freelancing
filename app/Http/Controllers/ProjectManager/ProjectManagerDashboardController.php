<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManagementRequest;

class ProjectManagerDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Get all projects managed by this PM
        $managedProjects = $user->managedProjects()->with('client', 'freelancer', 'milestones')->get();

        // Get pending requests (from clients)
        $pendingRequests = ManagementRequest::where('project_manager_id', $user->id)
            ->where('status', 'pending')
            ->with('project', 'client')
            ->get();

        // Count of pending requests
        $pendingCount = $pendingRequests->count();

        return view('project-manager.manager-dashboard', compact('managedProjects', 'pendingRequests', 'pendingCount'));
    }
}
