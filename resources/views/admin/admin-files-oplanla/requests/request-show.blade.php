@extends('layouts.admin')

@section('content')

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col flex-wrap items-center justify-between -mt-2 -ml-4">
                    <div class="mt-2 ml-4">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $request->booking->user->name }} - {{ $request->booking->id }}</h1>
                        <div class="mt-1 flex items-center">
                            @php
                                $endDate = new DateTime($request->created_at);
                                $formattedDate = $endDate->format('d F Y h:iA');
                            @endphp
                            <p class="text-sm text-gray-600 truncate">{{ $formattedDate }}</p>
                        </div>
                    </div>
                    @if($request->status == 'rejected')
                        <p>
                            Waiting for user to update request
                        </p>
                    @elseif($request->status == 'accepted')
                        <p>
                            User has already been accepted
                        </p>
                    @else
                        <div class="mt-4 ml-4 flex-shrink-0 flex space-x-2">
                            <a href="{{ route('admin.booking-requests.reject', $request->id) }}" class="btn-edit inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-[rgb(22 163 74)] hover:bg-[rgb(21 128 61)]">Accept Request</a>
                            <form action="{{ route('admin.booking-requests.reject', $request->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">Reject</button>
                            </form>
                        </div>
                    @endif
                    <div class="mt-2 ml-4">
                        <h1 class="text-2xl font-bold text-gray-800">User message</h1>
                        <div class="mt-1 flex items-center">
                            <p class="text-sm text-gray-600">{{ $request->message }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 p-6">
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900">Email Address</h2>
                    <div class="mt-2 text-sm text-gray-600 space-y-4" style="color: #e4ad68;">
                        <a href="mailto:{{$request->booking->user->email}}">{{ $request->booking->user->email }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection