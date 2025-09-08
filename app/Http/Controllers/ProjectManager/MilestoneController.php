<?php

namespace App\Http\Controllers\ProjectManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    public function index_client(Contract $contract){
        $milestones = $contract->milestones;
        return view('project-manager.Projects.tabs.payments', ['milestones' => $milestones]);
    }

    public function create(Contract $project) {
        return view('project-manager.projects.create-milestone', ['project' => $project, 'method' => 'POST', 'actionUrl' => route('pm.project.milestone.store', $project)]);
    }

    public function store(Request $request, Contract $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        Milestone::create([
            'project_id' => $project->id,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'amount' => $request->input('amount'),
            'due_date' => $request->input('due_date'),
            'status' => 'pending',
        ]);

        return redirect()->route('pm.project.show', $project->id)->with('status', 'Milestone created.');
    }

    public function edit(Contract $project, Milestone $milestone)
    {
        return view('project-manager.projects.create-milestone', ['project' => $project, 'milestone' => $milestone, 'method' => 'POST', 'actionUrl' => route('pm.project.milestone.update', [$project, $milestone])]);
    }

    public function update(Request $request, Milestone $milestone)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'required|date|after_or_equal:today',
        ]);

        $milestone->update($request->all());
        $milestone->save();

        return back()->with('status', 'Milestone updated.');
    }

    public function destroy(Milestone $milestone)
    {
        $milestone->delete();
        return back()->with('status', 'Milestone deleted.');
    }

    public function releasePayment(Contract $project, Milestone $milestone){
        $milestone->status = "released";
        $milestone->save();

        return back()->with('status', 'milestone payment released successfully');
    }
}
