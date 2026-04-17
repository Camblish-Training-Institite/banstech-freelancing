@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto py-10 sm:px-6 lg:px-8">
    {{-- Breadcrumbs / Back Navigation --}}
    <nav class="flex mb-5" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('admin.projects.show', $milestone->project_id) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600">
                    Back to Contract #{{ $milestone->project_id }}
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 11H3a1 1 0 110-2h7.586l-3.293-3.293a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-medium text-gray-400 md:ml-2">Milestone Details</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white shadow-xl rounded-lg overflow-hidden">
        {{-- Header Section --}}
        <div class="px-6 py-6 border-b border-gray-200 bg-gray-50 flex flex-wrap justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $milestone->title }}</h1>
                <p class="text-sm text-gray-500 mt-1">Created on {{ $milestone->created_at->format('M d, Y') }}</p>
            </div>
            <div class="flex space-x-3 mt-4 sm:mt-0">
                <a href="{{ route('admin.milestones.edit', $milestone->id) }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50">
                    Edit
                </a>
                <form action="{{ route('admin.milestones.destroy', $milestone->id) }}" method="POST" onsubmit="return confirm('Delete this milestone?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Left: Details --}}
                <div class="md:col-span-2 space-y-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Description</h3>
                        <div class="mt-2 text-gray-600 prose max-w-none">
                            {{ $milestone->description ?: 'No description provided for this milestone.' }}
                        </div>
                    </div>

                    {{-- Quick Status Update Controls --}}
                    <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100">
                        <h3 class="text-sm font-bold text-indigo-800 uppercase tracking-wider mb-4">Admin Status Control</h3>
                        <div class="flex flex-wrap gap-3">
                            <form action="{{ route('admin.milestones.update-status', $milestone->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="requested">
                                <button type="submit" class="px-4 py-2 bg-amber-600 text-white text-sm font-medium rounded hover:bg-amber-700 {{ $milestone->status == 'requested' ? 'opacity-50 cursor-not-allowed' : '' }}" @disabled($milestone->status == 'requested')>
                                    Mark as Requested
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.milestones.update-status', $milestone->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 {{ $milestone->status == 'approved' ? 'opacity-50 cursor-not-allowed' : '' }}" @disabled($milestone->status == 'approved')>
                                    Mark as Approved
                                </button>
                            </form>

                            <form action="{{ route('admin.milestones.update-status', $milestone->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="released">
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 {{ $milestone->status == 'released' ? 'opacity-50 cursor-not-allowed' : '' }}" @disabled($milestone->status == 'released')>
                                    Mark as Released
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right: Sidebar Stats --}}
                <div class="space-y-6">
                    <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase">Current Status</dt>
                                <dd class="mt-1">
                                    <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                        {{ $milestone->status == 'released' ? 'bg-green-100 text-green-800' : ($milestone->status == 'approved' ? 'bg-blue-100 text-blue-800' : ($milestone->status == 'requested' ? 'bg-amber-100 text-amber-800' : 'bg-yellow-100 text-yellow-800')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-semibold text-gray-500 uppercase">Due Date</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-bold">
                                    {{ $milestone->due_date ? \Carbon\Carbon::parse($milestone->due_date)->format('F d, Y') : 'No deadline' }}
                                </dd>
                            </div>
                            <div class="pt-4 border-t border-gray-200">
                                <dt class="text-xs font-semibold text-gray-500 uppercase">Linked Project</dt>
                                <dd class="mt-1 text-sm text-indigo-600 font-medium">
                                    <a href="{{ route('admin.projects.show', $milestone->project_id) }}" class="hover:underline">
                                        Contract #{{ $milestone->project_id }}
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection     
