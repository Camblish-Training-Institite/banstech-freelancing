@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 space-y-6">
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-medium text-indigo-600">Project #{{ $project->id }}</p>
                <h1 class="text-2xl font-bold text-gray-900">{{ $project->job->title }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Client: {{ $project->job->user->name }} |
                    Freelancer: {{ $project->user->name }} |
                    Manager: {{ $project->projectManager?->name ?? 'Not assigned' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.projects.edit', $project) }}" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                    Edit Project
                </a>
                <a href="{{ route('admin.projects.milestones.create', $project) }}" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                    Add Milestone
                </a>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Budget</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">R{{ number_format($project->agreed_amount, 2) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Milestones</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">{{ $project->milestones->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Released</p>
                <p class="mt-2 text-2xl font-bold text-gray-900">R{{ number_format($project->sumReleased(), 2) }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Status</p>
                <p class="mt-2">
                    <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold
                        {{ $project->status === 'completed' ? 'bg-green-100 text-green-800' : ($project->status === 'cancelled' ? 'bg-red-100 text-red-800' : ($project->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                        {{ ucfirst($project->status) }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Milestone Management</h2>
                        <p class="text-sm text-gray-500">Create, track, and update project payment milestones from the admin panel.</p>
                    </div>
                    <a href="{{ route('admin.milestones.index', ['project_id' => $project->id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        View all milestones
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Due Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($project->milestones as $milestone)
                                <tr>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('admin.milestones.show', $milestone) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                            {{ $milestone->title }}
                                        </a>
                                        <p class="mt-1 text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($milestone->description, 80) ?: 'No description provided.' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">R{{ number_format($milestone->amount, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $milestone->due_date?->format('M d, Y') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                            {{ $milestone->status === 'released' ? 'bg-green-100 text-green-800' : ($milestone->status === 'approved' ? 'bg-blue-100 text-blue-800' : ($milestone->status === 'requested' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($milestone->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap justify-end gap-2">
                                            <a href="{{ route('admin.milestones.edit', $milestone) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                                Edit
                                            </a>
                                            @foreach (['requested' => 'Requested', 'approved' => 'Approved', 'released' => 'Released'] as $statusValue => $statusLabel)
                                                @if ($milestone->status !== $statusValue)
                                                    <form method="POST" action="{{ route('admin.milestones.update-status', $milestone) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="{{ $statusValue }}">
                                                        <button type="submit" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50">
                                                            Mark {{ $statusLabel }}
                                                        </button>
                                                    </form>
                                                @endif
                                            @endforeach
                                            <form method="POST" action="{{ route('admin.milestones.destroy', $milestone) }}" onsubmit="return confirm('Delete this milestone?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-md border border-red-200 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-50">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No milestones have been created for this project yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-900">Project Details</h2>
                </div>
                <dl class="space-y-4 p-6 text-sm text-gray-700">
                    <div>
                        <dt class="font-semibold text-gray-500">Start Date</dt>
                        <dd class="mt-1">{{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('M d, Y') : 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">End Date</dt>
                        <dd class="mt-1">{{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->format('M d, Y') : 'Not set' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Job Description</dt>
                        <dd class="mt-1">{{ $project->job->description ?: 'No description provided.' }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Files Uploaded</dt>
                        <dd class="mt-1">{{ $project->files->count() }}</dd>
                    </div>
                    <div>
                        <dt class="font-semibold text-gray-500">Tasks Logged</dt>
                        <dd class="mt-1">{{ $project->tasks->count() }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
@endsection
