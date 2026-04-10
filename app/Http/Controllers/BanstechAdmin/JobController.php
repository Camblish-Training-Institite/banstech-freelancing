<?php

namespace App\Http\Controllers\BanstechAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{

    public function index(Request $request)
    {
        $query = Job::query();

        // Search Filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Job Type Filter
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Use paginate() instead of get()
        $jobs = $query->latest()->paginate(10);
        $pagetitle = "Manage Jobs";

        return view('admin.jobs.index', compact('jobs', 'pagetitle'));
    }

    public function show(Job $job)
    {
        $pagetitle = "Job Details";
        return view('admin.jobs.show', compact('job', 'pagetitle'));
    }

    public function create()
    {
        return view('admin.jobs.form', ['pagetitle' => 'Create Job', 'action' => 'Create']);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'budget' => 'required|numeric',
            'job_type' => 'required|in:physical,online',
            'category_id' => 'nullable|exists:categories,id',
            'skills' => 'nullable|array',
            'deadline' => 'nullable|date',
        ]);

        // Convert skills array to JSON as required by your schema
        $validated['skills'] = json_encode($request->skills);
        $validated['user_id'] = Auth::id(); // Admin creating on behalf of system

        Job::create($validated);
        return redirect()->route('admin.jobs.index')->with('success', 'Job created.');
    }

    public function edit(Job $job)
    {
        return view('admin.jobs.form', ['pagetitle' => 'Edit Job', 'action' => 'Edit', 'job' => $job]);
    }

    public function update(Request $request, Job $job)
    {
        // dd($request);
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,assigned,completed,cancelled',
            'budget' => 'numeric',
            'skills' => 'string',
            'description' => 'string',
            'job_type' => 'in:physical,online',

        ]);

        // dd($validated['job_type'], $job->job_type);

        $job->update([
            'status' => $validated['status'],
            'budget' => $validated['budget'] ?? $job->budget,
            'skills' => $validated['skills'] ?? $job->skills,
            'description' => $validated['description'] ?? $job->description,
            'job_type' => $validated['job_type'],
        ]);

        $job->save();
        return redirect()->route('admin.jobs.index')->with('success', 'Job updated.');
    }
}
