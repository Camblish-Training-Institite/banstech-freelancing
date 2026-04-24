<?php

namespace App\Http\Controllers\Freelancer;

use App\Models\Milestone;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\MilestoneReleased;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EndProjecetReminder;
use App\Models\User;
use App\Services\Payments\EscrowPayoutService;
use Illuminate\Support\Facades\Log;
use Throwable;

class MilestoneController extends Controller
{
    public function __construct(protected EscrowPayoutService $escrowPayoutService)
    {
    }

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
        // $this->authorizeRequester($milestone);

        $notification = Auth::user()->unreadNotifications->where('data.milestone_id', $milestone->id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return view('Users.Freelancers.projects.milestone-show', compact('milestone'));
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
        try {
            abort_unless($project->job->user_id === Auth::id(), 403);
            abort_unless($milestone->project_id === $project->id, 404);

            $this->escrowPayoutService->releaseMilestone($milestone);

            $freelancer = $milestone->project->user;
            $freelancer->notify(new MilestoneReleased($milestone->id));

            if($project->milestones->count() == $project->milestones->where('status', 'released')->count())
            {
                $client = User::find($project->job->user->id);
                $client->notify(new EndProjecetReminder($project));
            }

            return back()->with('status', 'Milestone payment released successfully.');
        } catch (Throwable $e) {
            Log::error('Milestone payment release failed', [
                'project_id' => $project->id,
                'milestone_id' => $milestone->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors([
                'payment' => $e->getMessage(),
            ]);
        }
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
