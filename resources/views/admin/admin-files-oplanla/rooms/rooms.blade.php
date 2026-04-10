{{-- resources/views/provider/rooms.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-8xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $pagetitle }}</h1>
                    <p class="mt-1 text-sm text-gray-600">A list of all rooms available at your properties.</p>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0">
                    <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add New Room
                    </a>
                </div>
            </div>
        </div>

        @if(session('error'))
            <div class="m-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="m-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col">
            <div class="-my-2 sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 hidden md:flex flex-col">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hotel & Room No.</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Is Rental</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facilities</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($rooms as $room)
                                    <tr class="hover:bg-gray-50 cursor-pointer" data-room-id="{{ $room->id }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $room->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $room->provider->provider_name }}</div>
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
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($room->rental)
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
                        
                        {{-- Card layout for mobile screens --}}
                        <div class="grid grid-cols-1 gap-4 px-4 py-4 md:hidden">
                            @forelse ($rooms as $room)
                                <div class="bg-white p-4 rounded-lg border shadow-sm space-y-3 cursor-pointer" data-room-id="{{ $room->id }}">
                                    <div class="items-center flex justify-between mb-2">
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $room->provider->provider_name }}</p>
                                            <!--<p class="text-xs text-gray-500">Room {{ $room->room_number }} (ID: #{{$room->id}})</p>-->
                                        </div>
                                        <div>
                                             @if ($room->available)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Available</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Not Available</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-3 gap-4 text-center">
                                        <div class="flex flex-col w-full items-start justify-start">
                                            <div>
                                                <p class="text-xs text-left text-gray-500">Room Type</p>
                                                <p class="text-sm font-medium text-gray-800 truncate">{{ $room->room_type }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-left text-gray-500">Price</p>
                                                <p class="text-sm font-medium text-gray-800">R {{ number_format($room->price, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Facilities</p>
                                        <p class="text-sm text-gray-800 truncate">{{ $room->room_facilities ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 px-6 text-center text-gray-500">
                                    You haven't added any rooms yet.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // MODIFIED: This script now targets any element with data-room-id, making it work for mobile and desktop.
    document.addEventListener("DOMContentLoaded", function () {
        const clickableElements = document.querySelectorAll("[data-room-id]");
        clickableElements.forEach(function (elem) {
            elem.addEventListener("click", function () {
                const roomId = this.getAttribute("data-room-id");
                window.location.href = `/admin/room/${roomId}`;
            });
        });
    });
</script>
@endsection
