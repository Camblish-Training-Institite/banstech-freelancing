@extends('layouts.app')

@section('content')
<h2 class="font-extrabold text-2xl mt-1">{{ isset($job) ? 'Edit Job' : 'Create Job' }}</h2>

<form action="{{ isset($job) ? route('jobs.update', $job) : route('jobs.store') }}" method="POST">
    @csrf 
    @include('client.form', ['job' => $job ?? null])
</form>
@endsection
