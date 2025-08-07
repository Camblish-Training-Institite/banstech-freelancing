@extends('layouts.app')

@section('content')
<h2 class="font-extrabold">Edit Job</h2>

<form action="{{ route('jobs.update', $job) }}" method="POST">
    @csrf
    @method('PUT')
    @include('client.form', ['job' => $job])
</form>
@endsection
