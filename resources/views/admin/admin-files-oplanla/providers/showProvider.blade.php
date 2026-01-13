{{-- resources/views/admin/showProvider.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('providers.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
            Back to Providers
        </a>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $provider->provider_name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $provider->booking_address }}</p>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0 flex space-x-2">
                    <a href="{{ route('admin.providers.edit', $provider->id) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Edit</a>
                    <form action="{{ route('admin.providers.delete', $provider->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Rooms Section --}}
        <div class="p-6">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Rooms</h2>
                <a href="{{ route('admin.rooms.create.single', $providerId) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">+ Add Room</a>
            </div>
            <div class="mt-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel & Room No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facilities</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rooms as $room)
                                    <tr class="hover:bg-gray-50 cursor-pointer" data-room-id="{{ $room->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $room->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $provider->provider_name }}</div>
                                            <div class="text-sm text-gray-500">Room {{ $room->room_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $room->room_type }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">R {{ number_format($room->price, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 text-center">{{ $room->num_people }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($room->available)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Yes</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">No</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs" title="{{ $room->room_facilities }}">
                                            {{ $room->room_facilities ?? 'No facilities listed' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                            You haven't added any rooms yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Contact Section --}}
        <div class="p-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                 <h2 class="text-lg font-semibold text-gray-900">Contact Information</h2>
                 @if ($provider->contact_id)
                    <a href="{{ route('contact.edit', [$providerId, $provider->contact_id]) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Edit Contact</a>
                 @else
                    <a href="{{ route('contact.create', $providerId) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">+ Add Contact</a>
                 @endif
            </div>
            <dl class="mt-4 grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $provider->phone ?? 'Not provided' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $provider->email ?? 'Not provided' }}</dd>
                </div>
            </dl>
        </div>
        {{-- Reviews Section would go here if needed --}}
    </div>
</div>
@endsection
