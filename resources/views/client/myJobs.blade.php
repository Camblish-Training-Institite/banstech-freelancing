@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Job Listings</h2>

    <a href="{{ route('jobs.create') }}" class="btn btn-primary mb-3">+ Create New Job</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Budget</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $job)
                <tr>
                    <td>{{ $job->title }}</td>
                    <td>${{ $job->budget }}</td>
                    <td><span class="badge bg-secondary">{{ $job->status }}</span></td>
                    <td>{{ $job->deadline }}</td>
                    <td>
                        <a href="{{ route('jobs.edit', $job) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this job?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $jobs->links() }}
</div>
@endsection
