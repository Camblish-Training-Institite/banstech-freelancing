@extends('dashboards.freelancer.dashboard')

@section('body')
<style>
    /* General Styles */
    body {
        font-family: Arial, sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        padding: 0;
        color: #333;
    }

    .container {
        width: 100%;
        margin: 30px auto;
        padding: 30px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Header-title */
    .header-title {
        display: flex;
        justify-content: space-between;
        align-items: left;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .header-title h1 {
        font-size: 28px;
        color: #6a51ae;
        margin: 0;
    }

    .back-link {
        color: #6a51ae;
        text-decoration: none;
        font-weight: bold;
        font-size: 16px;
    }

    .back-link:hover {
        text-decoration: underline;
    }

    /* Job Info Section */
    .job-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 25px;
    }

    .job-info div {
        margin-bottom: 10px;
    }

    .job-info label {
        font-weight: bold;
        color: #555;
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .job-info span {
        font-size: 16px;
        color: #333;
    }

    /* Tags */
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 1rem 0;
    }

    .tags span {
        background-color: #f0eaff;
        color: #6a51ae;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }

    /* Description */
    .description {
        font-size: 16px;
        line-height: 1.6;
        color: #555;
        padding: 1rem 0;
    }

    /* Budget & Proposals */
    .meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        padding: 15px;
        background-color: #f7f7f7;
        border-radius: 8px;
        font-size: 16px;
    }

    .meta .price {
        font-size: 18px;
        font-weight: bold;
        color: #333;
    }

    .meta .proposals {
        color: #666;
    }

    /* Action Buttons */
    .actions {
        display: flex;
        gap: 15px;
    }

    .actions button,
    .actions a {
        flex: 1;
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
    }

    .send-proposal {
        background-color: #6a51ae;
        color: white;
    }

    .send-proposal:hover {
        background-color: #5b419e;
    }

    .save-job {
        background-color: #e0e0e0;
        color: #333;
    }

    .save-job:hover {
        background-color: #d0d0d0;
    }

    .report-job {
        background-color: #f44336;
        color: white;
    }

    .report-job:hover {
        background-color: #d32f2f;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .job-info {
            grid-template-columns: 1fr;
        }

        .actions {
            flex-direction: column;
        }

        .container {
            padding: 20px;
        }
    }
</style>

 

<div class="flex w-full justify-start">
    <a href="{{ route('jobs.listing') }}" class="back-link">← Back to Projects</a>
</div>
 

<div class="container mx-auto p-4 md:p-8">
    <!-- header section -->
    <header class="mb-8">
        <div class="flex flex-wrap items-center pt-2 justify-between gap-4">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800">{{ $job->title ?? 'Job Title Not Available' }}</h1>
            <div class="flex items-center gap-4">
                <span
                    class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-700 bg-green-100 rounded-full">
                    {{ $job->status ?? 'Unknown' }}
                </span>
            </div>
        </div>
        <p class="mt-2 text-lg text-gray-600">
            Project Budget: <span class="font-semibold text-gray-700"> {{ 'R' . number_format($job->budget) . ' ZAR'
                }}</span>
        </p>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-8">

        <!-- main project details -->
        <main class="lg:col-span-2 space-y-8">

            <section class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Project Details</h2>
                    <span class="text-sm text-gray-500">Bidding ends on: {{
                        \Carbon\Carbon::parse($job->bidding_end_date)->format('d M, Y') }} </span>
                </div>

                <div class="prose max-w-none text-gray-600 space-y-4">
                    {!! nl2br(e($job->description ?? 'No description provided.')) !!}
                </div>

                <div class="mt-6">
                    <h3 class="mb-3 text-lg font-semibold text-gray-700">Skills</h3>
                    <div class="tags flex flex-wrap gap-2">
                        @php
                            $jobSkills = $job->skills ? explode(',',$job->skills) : [];
                        @endphp
                        @foreach($jobSkills as $skill)
                            <span
                                class="inline-block px-3 py-1 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">{{
                                trim($skill) }}</span>
                        @endforeach
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="pt-6 mt-6 text-right border-t border-gray-200 justify-between flex">
                    <a href="{{route('freelancer.proposal.create', $job->id)}}"
                        class="send-proposal inline-flex items-center px-4 py-2 border border-blue-600 text-sm font-medium text-blue-600 hover:text-blue-800 rounded-2">Send
                        Proposal
                    </a>
                    <div>
                        <button class="save-job inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 rounded-lg">
                            Save Job
                        </button>
                        <a href="#" class="report-job inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 hover:text-red-800 rounded-lg">
                            Report Job
                        </a>
                    </div>

                </div>
            </section>

            <!-- proposals section -->
            @if($job->proposals->count() > 0)
                <section class="p-6 bg-white text-center">
                    <h2 class="text-xl text-left font-bold text-gray-800 mb-4">Proposals ({{ $job->proposals->count() }})</h2>
                    @foreach($job->proposals as $proposal)

                        <div class="space-y-6 py-4">
                            <article class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <div class="flex-1 space-y-4">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <div>
                                                <div class="flex items-center space-x-2">
                                                    <div>
                                                        {{-- <img class="w-16 h-16 rounded-full object-cover" width="64" height="64"
                                                            src="{{ $proposal->user->profile ? asset('storage/' . $proposal->user->profile->avatar) : 'https://ui-avatars.com/api/?name= '.$proposal->user->name .'&background=random&size=128' }}"
                                                            alt="{{ $proposal->User->name }}"> --}}
                                                            @include('components.user-avatar', ['user' => $proposal->user, 'width' => '64', 'height' => '64'])
                                                    </div>
                                                    <div class="flex flex-col justify-start items-start">
                                                        <h4 class="text-lg text-left font-semibold text-gray-900">{{$proposal->user->name}}</h4>
                                                        @if ($proposal->user->reviews->count() > 0)
                                                            @include('components.review-stars', ['averageRating' => $proposal->user->reviews->avg('rating')])
                                                        @else
                                                            <span class="text-sm text-gray-500">No reviews yet</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-sm sm:text-base font-semibold text-gray-700">
                                                Bid amount: <span class="text-blue-600"> R{{ $proposal->bid_amount }}</span>
                                            </div>
                                        </div>
                                        <p class="text-left mt-4 text-gray-600 pl-4">
                                            {{ $proposal->cover_letter }}
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                    @else

                        <h2 class="text-xl font-bold text-gray-800 mb-4">No Proposals Yet</h2>
                        <p class="text-gray-600">Be the first to submit a proposal for this job!</p>
                </section>
            @endif
        </main>

 
        <div class="flex flex-col gap-6 mb-6 mt-8 lg:mt-0">
            @include('Users.Freelancers.components.about-client')
            {{-- <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <a href="{{url('/main_map')}}" style="display: block; text-decoration: none; color: inherit;">
                    @include('geo_location.mini_map')
                </a>
            </div>  --}}
        </div> 
    </div>
</div>
@endsection