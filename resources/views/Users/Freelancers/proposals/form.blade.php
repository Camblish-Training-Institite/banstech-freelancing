@extends('dashboards.freelancer.dashboard')

@section('body')
    @if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-6" >
        <ul class="list-disc pl-5 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul> 
        </div>
    @endif  

    <style>
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
    </style>

    <h2 class="font-extrabold text-2xl mt-1 mb-6">{{isset($proposal) ? 'Updating' : 'Creating'}} Proposal for Job: {{$job->title}}</h2>

    <form action="{{ $actionUrl }}" method="POST">
        @csrf
        @method($method)

        <!-- job id label -->
        <input type="hidden" name="job_id" value="{{ $job->id }}">

        {{-- Bid Amount and Cover Letter Fields --}}
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w mx-auto">
            <div class="space-y-6">
                {{-- Bid Amount --}}
                <div class="flex flex-col w-full gap-4 py-2">
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-semibold text-gray-600 mb-2">Job Budget:</label>
                        <input
                            type="text"
                            class="w-full p-1  border rounded-md text-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            style="background-color: #f0eaff; color: #6a51ae;"
                            value="{{ number_format($job->budget, 2) }}"
                            disabled
                        >
                    </div>
                    <div class="flex-1 w-full">
                        <label for="bid_amount" class="block text-sm font-semibold text-gray-600 mb-2">Bid Amount</label>
                        <input
                            type="text"
                            name="bid_amount"
                            id="bid_amount"
                            class="w-full p-1  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('bid_amount', $proposal->bid_amount ?? '') }}"
                            required
                        >
                    </div>
                </div>

                <!-- Tags -->
                @if(!empty($job->skills))
                    <div class="tags">
                        @php
                            $jobSkills = $job->skills ? explode(',',$job->skills) : [];
                        @endphp
                        @foreach($jobSkills as $skill)
                            <span>{{ trim($skill) }}</span>
                        @endforeach
                    </div>
                @endif


                {{-- Project cover_letter --}}
                <div>
                    <label for="cover_letter" class="block text-sm font-semibold text-gray-600 mb-2">Cover Letter</label>
                    <textarea
                        name="cover_letter"
                        id="cover_letter"
                        rows="5"
                        class="w-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >{{ old('cover_letter', $proposal->cover_letter ?? '') }}</textarea>
                </div>
            </div>


            {{-- Submit Button --}}
            <div class="mt-4">
                <button
                    type="submit"
                    class="btn btn-success p-1 text-white  hover:bg-green-600 
                    transition duration-300">
                    {{ isset($proposal) ? 'Update Proposal' : 'Create Proposal' }}
                </button>
            </div>
            
        </div>
    </form>

@endsection