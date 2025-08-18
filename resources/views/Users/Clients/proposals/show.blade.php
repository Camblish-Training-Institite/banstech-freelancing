@extends('dashboards.client.dashboard')

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
            max-width: 1000px;
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
            width:100%;
            margin-bottom: 20px;
            margin-top: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .header-title h1 {
            font-size: 28px;
            color: #516aae;
            margin: 0;
        }
        .back-link {
            color: #516aae;
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
            color: #516aae;
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
        .actions button, .actions a {
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
            background-color: #516aae;
            color: white;
        }
        .send-proposal:hover {
            background-color: #253050;
        }
        .save-job {
            background-color: #e0e0e0;
            color: #333;
        }
        .save-job:hover {
            background-color: #d0d0d0;
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

    <div class="container">
        <!-- Header-title -->
        <div class="flex w-full justify-start">
            <a href="{{ url()->previous() }}" class="back-link">← Back to Jobs</a>
        </div>
        
        <div class="header-title">
            <h1>{{ $job->title ?? 'Job Title Not Available' }}</h1>
            {{-- <a href="{{ url()->previous() }}" class="back-link">← Back to Jobs</a> --}}
        </div>

        <!-- Job Info -->
        <div class="job-info">
            <div>
                <label>Client</label>
                <span>{{ $job->user->name ?? 'Anonymous' }}</span>
            </div>
            <div>
                <label>Posted On</label>
                <span>{{ $job->created_at?->format('M d, Y') ?? 'N/A' }}</span>
            </div>
            {{-- <div>
                <label>Type</label>
                <span>{{ ucfirst($job->type ?? 'project') }}</span>
            </div>
            <div>
                <label>Location</label>
                <span>{{ $job->location ?? 'Remote' }}</span>
            </div> --}}
        </div>

        <!-- Description -->
        <div class="description">
            {!! nl2br(e($job->description ?? 'No description provided.')) !!}
        </div>

        <!-- Tags -->
        @if(!empty($job->skills))
            <div class="tags">
                @php
                    $jobSkills = $job->skills ? explode(',', $job->skills) : [];
                    // dd($jobSkills);
                    $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes
                @endphp
                @foreach($jobSkills as $skill)
                    <span>{{ trim($skill) }}</span>
                @endforeach
            </div>
        @endif

        <!-- Budget & Proposals -->
        <div class="meta">
            <div class="price">
                {{ 'R' . number_format($job->budget) . ' ZAR' }}
            </div>
            {{-- <div class="proposals">
                {{ $job->proposals_count ?? 0 }} Proposal{{ $job->proposals_count != 1 ? 's' : '' }}
            </div> --}}
        </div>

        <!-- Action Buttons -->
        <div class="bg-white overflow-hidden mt-6">
            <div class="px-2 flex space-x-4 justify-end">
                <a href="{{ route('client.jobs.edit', $job->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Edit Job
                </a>

                <form action="{{ route('client.jobs.destroy', $job->id) }}" method="POST"
                    onsubmit="return confirm('Are you sure you want to delete this contest?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete Job
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- Contest proposals Section -->
    @if($Job->proposals->isNotEmpty())
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">proposals Received ({{
                $Job->proposals->count() }})</h3>

            <div class="space-y-4">
                @foreach($Job->proposals as $proposal)
                <div class="border rounded-lg p-4 hover:bg-gray-50">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">{{ $proposal->user->name }}</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('client.proposal.show', $proposal) }}"
                                class="text-indigo-600 text-sm hover:underline">View</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection