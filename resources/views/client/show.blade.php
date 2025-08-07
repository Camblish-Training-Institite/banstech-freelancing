@extends('layouts.app')

@section('content')
<h2>{{ $job->title }}</h2>

<p>{{ $job->description }}</p>
<p>Budget: ${{ $job->budget }}</p>
<p>Status: {{ ucfirst(str_replace('_', ' ', $job->status)) }}</p>
<p>Deadline: {{ $job->deadline }}</p>

@if ($job->skills)
    <p>Skills: {{ implode(', ', $job->skills) }}</p>
@endif

<a href="{{ route('jobs.edit', $job) }}">Edit</a>
@endsection
