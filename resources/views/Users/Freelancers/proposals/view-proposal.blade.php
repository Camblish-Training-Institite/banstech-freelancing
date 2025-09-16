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
            grid-template-columns: repeat(3, 1fr);
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

        /* cover_letter */
        .cover_letter {
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
        <div class="flex w-full justify-start mb-4">
            <a href="{{ url()->previous() }}" class="back-link">← Back</a>
        </div>
        <div class="header-title flex">
            <h1>{{ $proposal->job->title ?? 'Job Title Not Available' }}</h1>
            <div>
                <span class="px-4 py-2 rounded-full text-md {{ ($proposal->status == 'accepted' ? 'text-green-800 bg-green-200' : $proposal->status == 'rejected') ? 'text-red-800 bg-red-100' : 'text-yellow-800 bg-yellow-300' }}">{{$proposal->status}}</span>
            </div>
        </div>

        <!-- Job Info -->
        <div class="job-info">
            <div>
                <label>Client</label>
                <span>{{ $proposal->job->user->name ?? 'Anonymous' }}</span>
            </div>
            <div>
                <label>Posted On</label>
                <span>{{ $proposal->created_at?->format('M d, Y') ?? 'N/A' }}</span>
            </div>
            <div>
                <label>Job Budget</label>
                <span>R {{ number_format($proposal->job->budget, 2) }}</span>
            </div>
            {{--<div>
                <label>Location</label>
                <span>{{ $proposal->location ?? 'Remote' }}</span>
            </div> --}}
        </div>

        <!-- cover_letter -->
        <div class="cover_letter">
            {!! nl2br(e($proposal->cover_letter ?? 'No cover letter provided.')) !!}
        </div>

        <!-- Tags -->
        @if(!empty($proposal->job->skills))
            <div class="tags">
                @php
                    $proposalSkills = $proposal->job->skills ? explode(',', $proposal->job->skills) : [];
                @endphp
                @foreach($proposalSkills as $skill)
                    <span>{{ trim($skill) }}</span>
                @endforeach
            </div>
        @endif

        <!-- Budget & Proposals -->
        <div class="meta">
            <div class="price">
                {{ 'R' . number_format($proposal->bid_amount) . ' ZAR' }}
            </div>
            {{-- <div class="proposals">
                {{ $proposal->proposals_count ?? 0 }} Proposal{{ $proposal->proposals_count != 1 ? 's' : '' }}
            </div> --}}
        </div>

        <!-- Action Buttons -->
        <div class="bg-white overflow-hidden mt-6">
            <div class="px-2 flex space-x-4 justify-end">
                <a href="{{ route('freelancer.proposals.edit', $proposal->id) }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Edit Job
                </a>

                <form action="{{ route('freelancer.proposals.destroy', $proposal->id) }}" method="POST"
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
@endsection