@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">Accept Proposal</h1>
            <p class="mt-2 text-sm text-gray-600">
                Choose how many milestones this project should start with. If you leave it blank, the project will be created with one milestone for the full agreed amount.
            </p>
        </div>

        <div class="p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Freelancer</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $proposal->user->name }}</p>
                </div>
                <div class="rounded-lg bg-gray-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500">Agreed Amount</p>
                    <p class="mt-1 text-lg font-semibold text-gray-900">R{{ number_format($proposal->bid_amount, 2) }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('client.proposals.accept.store', $proposal) }}" class="space-y-6">
                @csrf

                <div>
                    <label for="milestone_count" class="block text-sm font-medium text-gray-700">Number of milestones</label>
                    <input
                        type="number"
                        id="milestone_count"
                        name="milestone_count"
                        min="1"
                        max="50"
                        value="{{ old('milestone_count') }}"
                        placeholder="Leave blank for a single milestone"
                        class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    <p class="mt-2 text-sm text-gray-500">
                        If you enter a number, the agreed amount will be split equally across that many milestones.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center px-4 py-2 rounded-md bg-green-600 text-white text-sm font-semibold hover:bg-green-700">
                        Create Project
                    </button>
                    <button type="submit" name="milestone_count" value="" class="inline-flex items-center px-4 py-2 rounded-md bg-gray-200 text-gray-800 text-sm font-semibold hover:bg-gray-300">
                        Skip And Use One Milestone
                    </button>
                    <a href="{{ route('client.proposals.show', $proposal) }}" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
