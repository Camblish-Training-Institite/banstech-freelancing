@extends('layouts.project-manager')

@section('content')
<div class="flex flex-col w-full h-full items-start">
    <div class="col-md-12">
        <h2 class="text-2xl mb-6 font-bold text-left" 
            style="
                padding:1rem 2rem;
                            
            "
        >Project Manager Dashboard</h2>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Pending Management Requests ({{ $pendingCount }})</h5>
            </div>
            <div class="card-body">
                @if ($pendingRequests->isEmpty())
                    <p>No pending requests.</p>
                @else
                    <ul class="list-group">
                        @foreach ($pendingRequests as $req)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <strong>{{ $req->project->title }}</strong>
                                <small>by {{ $req->client->name }}</small>
                                <div>
                                    <a href="{{ route('admin.management-requests.accept', $req->id) }}" class="btn btn-success btn-sm">Accept</a>
                                    <a href="{{ route('admin.management-requests.reject', $req->id) }}" class="btn btn-danger btn-sm">Reject</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Your Managed Projects ({{ $managedProjects->count() }})</h5>
            </div>
            <div class="card-body">
                @if ($managedProjects->isEmpty())
                    <p>You are not managing any projects yet.</p>
                @else
                    <div class="row">
                        @foreach ($managedProjects as $project)
                            <div class="col-md-6 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $project->title }}</h5>
                                        <p class="text-muted">Client: {{ $project->client->name }}</p>
                                        <p>Milestones: {{ $project->milestones->count() }}</p>
                                        <a href="{{ route('admin.projects.show', $project->id) }}" class="btn btn-primary btn-sm">View Project</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection