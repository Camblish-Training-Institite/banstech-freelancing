<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    public function index(Request $request)
    {
        $milestones = Milestone::query()->with(['project.job.user', 'project.user', 'project.projectManager']);

        if ($request->filled('search')) {
            $search = $request->search;
            $milestones->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status')) {
            $milestones->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $milestones->where('project_id', $request->project_id);
        }

        $milestones = $milestones->latest()->paginate(10);

        return view('admin.Milestones.index', compact('milestones'));
    }

    public function show(Milestone $milestone)
    {
        $milestone->load(['project.job.user', 'project.user', 'project.projectManager']);

        return view('admin.Milestones.show', ['milestone' => $milestone]);
    }

    public function create(Contract $project)
    {
        $action = 'Create';
        return view('admin.Milestones.form', compact('project', 'action'));
    }

    public function store(Request $request, Contract $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,requested,approved,released',
        ]);

        Milestone::create([
            'project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
        ]);

        return redirect()->route('admin.projects.show', $project)
                        ->with('success', 'Milestone created successfully.');
    }

    public function edit(Milestone $milestone)
    {
        $project = Contract::findOrFail($milestone->project_id);
        $action = 'Edit';
        return view('admin.Milestones.form', compact('milestone', 'project', 'action'));
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,requested,approved,released',
        ]);

        $milestone->update($validated);

        return redirect()->route('admin.projects.show', $milestone->project_id)
                        ->with('success', 'Milestone updated successfully.');
    }

    public function destroy(Milestone $milestone)
    {
        $projectId = $milestone->project_id;
        $milestone->delete();

        return redirect()->route('admin.projects.show', $projectId)
                        ->with('success', 'Milestone deleted successfully.');
    }


    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,requested,approved,released',
        ]);

        $milestone = Milestone::findOrFail($id);
        $milestone->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Milestone status updated to ' . ucfirst($request->status));
    }
}
