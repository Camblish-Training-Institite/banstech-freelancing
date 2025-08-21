<?php

namespace App\Http\Controllers\Client;

use App\Models\Project;
use App\Models\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = \Auth::user()->projects()->latest()->get();
        return view('dashboards.client.projects.index', compact('projects'));
    }

    protected function authorizeOwner(Project $project)
    {
        if ($project->user_id !== \Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function show(Project $project)
    {
        $this->authorizeOwner($project);
        return view('dashboards.client.projects.show', compact('project'));
    }

    public function createMilestone(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $project->milestones()->create($request->all());

        return back()->with('status', 'Milestone created.');
    }
}
