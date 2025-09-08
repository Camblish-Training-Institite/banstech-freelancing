@extends('dashboards.freelancer.dashboard')
@section('body')

<div class="bg-gray-100 py-12">
    <div class="max-w mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center text-sm font-medium text-gray-600  hover:text-gray-900 ">
                <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                Back to All Contests
            </a>
        </div>

        <div class="lg:grid- mb-6 lg:gap-8 p-6" style="background-color: #2c2c2c">
            <h2 class="text-3xl font-extrabold text-white ">{{ $contest->title }}</h2>
            <p class="text-gray-300 ">Hosted by {{ $contest->client->name ?? 'Anonymous' }}</p>
        </div>

        <div class="bg-white p-4 rounded-lg mb-6">
            @include('Users.Freelancers.contests.components.nav-bar')
        </div>


        <!-- Main Content Area -->
        <div class="py-6">
            <div x-show="tab === 'details'">
                @include('Users.Freelancers.contests.details')
            </div>
            <div x-show="tab === 'entries'">
                @include('Users.Freelancers.contests.entries')
            </div>
        </div>
</div>
@endsection