@extends('users.clients.layouts.dashboard-body')
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

                        <div class="mb-4">
                            <label for="prize_money" class="block text-sm font-medium text-gray-700">Prize Money</label>
                            <input type="number" id="prize_money" name="prize_money" step="0.01" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="closing_date" class="block text-sm font-medium text-gray-700">Closing Date</label>
                            <input type="date" id="closing_date" name="closing_date" class="mt-1 block w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="required_skills" class="block text-sm font-medium text-gray-700">Required Skills</label>
                            <input type="text" id="required_skills" name="required_skills[]" placeholder="Skill 1, Skill 2, Skill 3" class="mt-1 block w-full">
                            <small>Separate skills with commas.</small>
                        </div>

                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Create Contest</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection