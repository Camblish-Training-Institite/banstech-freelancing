<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\Milestone;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MilestoneController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $milestones = $user->milestones()->latest()->get();
        return view('Users.Freelancers.layouts.milestone-section', compact('milestones'));
    }

    public function index_client(Contract $contract){
        $milestones = $contract->milestones;
        return view('Users.Clients.Projects.tabs.payments', ['milestones' => $milestones]);
    }

    public function create(Contract $project) {
        return view('Users.Clients.projects.create-milestone', ['project' => $project, 'method' => 'POST', 'actionUrl' => route('client.project.milestone.store', $project)]);
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
            'status' => 'approved',
        ]);

        return redirect()->route('client.project.show', $project->id)->with('status', 'Milestone created.');
    }

    public function show(Milestone $milestone)
    {
        $this->authorizeRequester($milestone);
        return view('Users.Freelancers.milestones.show', compact('milestone'));
    }

    public function edit(Contract $project, Milestone $milestone)
    {
        return view('Users.Clients.projects.create-milestone', ['project' => $project, 'milestone' => $milestone, 'method' => 'POST', 'actionUrl' => route('client.project.milestone.update', [$project, $milestone])]);
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

    public function request(Milestone $milestone)
    {
        $this->authorizeRequester($milestone);
        $milestone->request();
        return back()->with('status', 'Milestone requested.');
    }

    private function authorizeRequester(Milestone $milestone)
    {
        if ($milestone->project->freelancer_id !== \Illuminate\Support\Facades\Auth::user()->id) {
            abort(403);
        }
    }
}