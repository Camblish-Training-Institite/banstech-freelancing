@extends('layouts.admin')

@section('content')
<div class="mx-auto py-10 sm:px-6 lg:px-8" style="width: 80%;">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">User Profile: {{ $user->name }}</h1>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-white border border-gray-300 rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Edit User</a>
            <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST">
                @csrf
                <button type="submit" class="bg-yellow-500 text-white rounded-md px-4 py-2 text-sm font-medium hover:bg-yellow-600">Send Password Reset</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="h-24 w-24 rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                @include('components.user-avatar', ['user' => $user, 'size' => '6rem'])
            </div>
            <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->email }}</p>
            <div class="mt-4 flex flex-wrap justify-center gap-2">
                @foreach($user->getRoleNames() as $role)
                    <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">{{ ucfirst($role) }}</span>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-2 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Account Details</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">User Type</dt>
                        <dd class="mt-1 text-sm text-gray-900 font-semibold">{{ ucfirst($user->user_type ?? 'N/A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                        <dd class="mt-1">
                            @if($user->is_verified)
                                <span class="text-green-600 flex items-center text-sm font-medium">
                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg> Verified
                                </span>
                            @else
                                <span class="text-red-500 text-sm font-medium">Not Verified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('F d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Login</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="md:col-span-3 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Jobs Posted by this User</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Budget</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($user->jobs as $job)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-indigo-600"><a href="{{ route('admin.jobs.show', $job->id) }}">{{ $job->title }}</a></td>
                                <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($job->budget, 2) }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $job->status == 'completed' ? 'bg-green-100' : 'bg-blue-100' }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right text-sm text-gray-500">{{ $job->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No jobs posted yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection