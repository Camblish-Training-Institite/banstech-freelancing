@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Milestones</h1>
                <p class="text-sm text-gray-500">Review milestone progress across the freelancing platform.</p>
            </div>
        </div>

        <form action="{{ route('admin.milestones.index') }}" method="GET" class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            @if (request('project_id'))
                <input type="hidden" name="project_id" value="{{ request('project_id') }}">
            @endif
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-xs font-medium uppercase text-gray-700">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Milestone title or description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium uppercase text-gray-700">Status</label>
                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300 p-2 shadow-sm">
                        <option value="">All statuses</option>
                        @foreach (['pending', 'requested', 'approved', 'released'] as $status)
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.milestones.index') }}" class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Milestone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Due Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($milestones as $milestone)
                        <tr>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.milestones.show', $milestone) }}" class="font-medium text-gray-900 hover:text-indigo-600">
                                    {{ $milestone->title }}
                                </a>
                                <p class="mt-1 text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($milestone->description, 70) ?: 'No description provided.' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <a href="{{ route('admin.projects.show', $milestone->project) }}" class="font-medium text-indigo-600 hover:text-indigo-800">
                                    {{ $milestone->project->job->title }}
                                </a>
                                <p class="mt-1 text-xs text-gray-500">Client: {{ $milestone->project->job->user->name }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">R{{ number_format($milestone->amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $milestone->due_date?->format('M d, Y') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold
                                    {{ $milestone->status === 'released' ? 'bg-green-100 text-green-800' : ($milestone->status === 'approved' ? 'bg-blue-100 text-blue-800' : ($milestone->status === 'requested' ? 'bg-amber-100 text-amber-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($milestone->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-wrap justify-end gap-2">
                                    <a href="{{ route('admin.milestones.edit', $milestone) }}" class="rounded-md border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                        Edit
                                    </a>
                                    <a href="{{ route('admin.projects.show', $milestone->project) }}" class="rounded-md border border-indigo-200 px-3 py-1.5 text-xs font-medium text-indigo-700 hover:bg-indigo-50">
                                        Open Project
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                No milestones found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
            {{ $milestones->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
