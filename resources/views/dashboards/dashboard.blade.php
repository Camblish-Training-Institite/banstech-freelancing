@extends('layouts.app')

@section('content')

    @if ($errors->any())
        <div class="mb-4 font-medium text-sm text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Existing status and error messages -->
    @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-4 font-medium text-sm text-red-600">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col items-center justify-center min-h-screen bg-blue-500">
        <p>Welcome to user dashboard</p>
    </div>
@endsection
