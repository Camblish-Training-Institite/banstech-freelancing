@extends('dashboards.Client.dashboard')

@section('body')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Contest</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('client.contests.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" rows="4" class="mt-1 block w-full" required></textarea>
                        </div>

                {{-- File Upload --}}
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Attach Files (Optional)</h3>
                    <div class="mb-4">
                        <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Upload New File</label>
                        <input type="file" name="file" id="file"
                            class="shadow-md appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('file') border-red-500 @enderror"
                            >
                        @error('file')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>
               

                        <div class="mb-4">
                            <label for="prize_money" class="block text-sm font-medium text-gray-700">Prize Money</label>
                            <input type="number" id="prize_money" name="prize_money" step="0.01" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="closing_date" class="block text-sm font-medium text-gray-700">Closing Date</label>
                            <input type="date" id="closing_date" name="closing_date" class="mt-1 block w-full" required>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Facilities</h3>
                            <p class="text-sm text-gray-500">Select the facilities available in this room.</p>
                            <div class="mt-2">
                                <input type="text" id="facility-input" placeholder="Search for a facility..." class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <ul id="suggestions" class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
                            </div>
                            <div id="selected-facilities" class="mt-2 space-x-2 space-y-2">
                                {{-- Selected facilities will appear here --}}
                            </div>
                            <input type="hidden" name="required_skills" id="required_skills" value=""> <!--  old('required_skills', $contest->required_skills)  -->
                        </div>

                        {{-- <div class="mb-4">
                            <label for="required_skills" class="block text-sm font-medium text-gray-700">Required Skills</label>
                            <input type="text" id="required_skills" name="required_skills[]" placeholder="Skill 1, Skill 2, Skill 3" class="mt-1 block w-full">
                            <small>Separate skills with commas.</small>
                        </div> --}}

                        <button 
                            type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                            style="background-color: rgb(59 130 246); color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; font-weight: 500;"
                        >Create Contest</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- @if ($table == 'Room')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Facilities</h3>
            <p class="text-sm text-gray-500">Select the facilities available in this room.</p>
            <div class="mt-2">
                <input type="text" id="facility-input" placeholder="Search for a facility..." class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <ul id="suggestions" class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
            </div>
            <div id="selected-facilities" class="mt-2 space-x-2 space-y-2">
                {{-- Selected facilities will appear here 
            </div>
            <input type="hidden" name="required_skills" id="required_skills" value="{{ old('required_skills', $room->required_skills) }}">
        </div>
    @endif --}}

<!-- script for tags -->
@include('components.tag-script')

@endsection