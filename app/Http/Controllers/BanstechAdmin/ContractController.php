<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\Job;
use App\Models\User;
use App\Notifications\ReviewFreelancerReminder;
use Illuminate\Database\Eloquent\Builder;

class ContractController extends Controller
{
    public function index(Request $request){

        $projects = Contract::query()->with(['job.user', 'user', 'projectManager', 'milestones']);

        if($request->filled('search')){
            $search = $request->search;

            $projects->where(function (Builder $query) use ($search) {
                $query->whereHas('job', function (Builder $jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                })->orWhereHas('job.user', function (Builder $clientQuery) use ($search) {
                    $clientQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('user', function (Builder $freelancerQuery) use ($search) {
                    $freelancerQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        if($request->filled('status')){
            $projects->where('status', $request->status);
        }

        if($request->filled('project_manager_id')){
            $projects->where('project_manager_id', $request->project_manager_id);
        }

        $projects = $projects->latest()->paginate(10);

        $pagetitle = 'Project Management';
        $managers = User::where('user_type', 'project-manager')->orderBy('name')->get();

        return view('admin.projects.index', compact('projects', 'pagetitle', 'managers'));
    }

    public function show(Contract $project)
    {
        $project->load([
            'job.user',
            'user',
            'projectManager',
            'milestones' => fn ($query) => $query->latest(),
            'files',
            'tasks',
        ]);

        return view('admin.projects.show', ['project' => $project]);
    }

    public function create(){
        $project = new Contract();
        $jobs = Job::where('status', 'open')->get();
        $freelancers = User::where('user_type', 'freelancer-client')->get();
        $managers = User::where('user_type', 'project-manager')->get();
        return view('admin.projects.form', ['pagetitle' => 'Create Project', 'action' => 'Create', 'jobs' => $jobs, 'freelancers' => $freelancers, 'managers' => $managers, 'project' => $project]);
    }

    public function store(Request $request){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'agreed_amount' => 'required|numeric',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        Contract::create([
            'user_id' => $validated['user_id'],
            'job_id' => $validated['job_id'],
            'agreed_amount' => $validated['agreed_amount'],
            'start_date' => now(),
            'end_date' => $validated['end_date'],
            'project_manager_id' => $validated['project_manager_id'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function edit(Contract $project){
        $jobs = Job::all();
        $freelancers = User::where('user_type', 'freelancer-client')->get();
        $managers = User::where('user_type', 'project-manager')->get();
        return view('admin.projects.form', ['project' => $project, 'pagetitle' => 'Edit Project', 'action' => 'Edit', 'jobs' => $jobs, 'freelancers' => $freelancers, 'managers' => $managers]);
    }

    public function update(Request $request, Contract $project){
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'agreed_amount' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_manager_id' => 'nullable|exists:users,id',
            'status' => 'required|in:pending,active,completed,cancelled',
        ]);

        $project->update([
            'user_id' => $validated['user_id'] ?? $project->user_id,
            'job_id' => $validated['job_id'] ?? $project->job_id,
            'agreed_amount' => $validated['agreed_amount'] ?? $project->agreed_amount,
            'start_date' => $validated['start_date'] ?? $project->start_date,
            'end_date' => $validated['end_date'] ?? $project->end_date,
            'project_manager_id' => $validated['project_manager_id'] ?? $project->project_manager_id,
            'status' => $validated['status'] ?? $project->status,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }



    public function cancelContract(Request $request, int $id){
        $project = Contract::find($id);
        
        $project->status = 'cancelled';
        $project->save();
        return redirect()->back()->with('success', 'Contract cancelled successfully.');
    }

    public function completeContract(Request $request, int $id){
        $project = Contract::find($id);
        $project->status = 'completed';
        $project->save();

        $client = User::find($project->user_id);
        $client->notify(new ReviewFreelancerReminder($project));

        return redirect()->back()->with('success', 'Contract completed successfully.');
    }
}
