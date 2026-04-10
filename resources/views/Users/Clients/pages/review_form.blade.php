@extends('dashboards.client.dashboard')

@section('body')
    <div class="flex flex-col w-full items-center justify-center">
        @php
            if($freelancer ?? false){
                // dd($freelancer);
            } else {
                $freelancer = $projectManager;
            }
            // dd($job);
        @endphp
        <header class="flex p-4 w-full max-w-6xl justify-between items-center">
            <div style="flex:1; 1px;">
                <a href="{{ url()->previous() }}" class="text-blue-600 hover:underline">← Back to Project</a>
            </div>
            <div style="flex:2; 1px; text-align: center;">
                <h2 class="text-3xl font-bold">Create Review for <span class="text-blue-500">{{ $freelancer? $freelancer->name : 'freelancer' }}</span> </h2>
            </div>
            <div style="flex:1; 1px;"></div>
        </header>
        <div class="max-w-6xl py-6 px-4 rounded-lg shadow-md border" style="width:100%;">
            <h2 class="text-2xl font-bold mb-4">Submit a Review</h2>
            <form method="POST" action="{{ route('client.reviews.store') }}">
                @csrf
                <div class="mb-4">

                    <input type="hidden" name="freelancer_id" value="{{ $freelancer->id }}">
                    <input type="hidden" name="contract_id" value="{{ $project->id }}">

                    <label for="rating" class="block text-gray-700 font-semibold mb-2">Rating:</label>
                    <select id="rating" name="rating" class="w-full border border-gray-300 rounded px-3 py-2">
                        <option value="">Select Rating</option>
                        @for ($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                    @error('rating')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-gray-700 font-semibold mb-2">Comment:</label>
                    <textarea id="comment" name="comment" rows="4" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="Write your review here..."></textarea>
                    @error('comment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-500">Submit Review</button>
            </form>
        </div>
    </div>
@endsection