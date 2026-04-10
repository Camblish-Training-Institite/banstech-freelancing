{{-- 4. resources/views/admin/booking_requests/index.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-gray-800">Booking Change Requests</h1>
            <p class="mt-1 text-sm text-gray-600">Review pending cancellation and edit requests from users.</p>
        </div>
        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking & User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Message</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-indigo-600 hover:text-indigo-900"><a href="#">Booking #{{ $request->booking_id }}</a></div>
                                    <div class="text-sm text-gray-500">{{ $request->booking->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $request->type == 'cancellation' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($request->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 max-w-md truncate" title="{{ $request->message }}">{{ $request->message }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.booking-request.show', $request->id) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center py-12 text-gray-500">No pending requests.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
