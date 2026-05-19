@extends('project-manager.manager-dashboard')

@section('body')
<div class="flex flex-col w-full h-full items-start">
    <div class="col-md-12">
        <h2 class="pm-page-title text-2xl mb-6 font-bold text-left"
        >Overview</h2>

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
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.management-requests.accept', $req->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.management-requests.reject', $req->id) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
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
                                        <h5 class="card-title">{{ $project->job->title }}</h5>
                                        <p class="text-muted">Client: {{ $project->job->user->name }}</p>
                                        <p>Milestones: {{ $project->milestones->where('status', '=', 'released')->count() }}/{{$project->milestones->count()}}</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <a href="{{ route('pm.project.show', $project->id) }}" class="btn btn-primary btn-sm mt-4">View Project</a> 
                                            <form method="POST" action="{{ route('pm.inbox.project.start', $project) }}" class="mt-4">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm">Contact Client</button>
                                            </form>
                                        </div>
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
