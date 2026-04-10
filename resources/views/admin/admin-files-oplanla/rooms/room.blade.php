{{-- resources/views/provider/room.blade.php --}}
@extends('layouts.admin')

@section('content')
{{-- This script is preserved to maintain functionality --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const url = window.location.href;
        const roomidMatch = url.match(/admin\/room\/(\d+)/);
        if (roomidMatch && roomidMatch[1]) {
            let roomId = parseInt(roomidMatch[1], 10);
            const editButtonLink = document.querySelector('.btn-edit');
            if (editButtonLink) {
                editButtonLink.href = "{{ route('admin.rooms.edit', '__ROOM_ID__') }}".replace('__ROOM_ID__', roomId);
            }
            const deleteForm = document.querySelector('.form-delete');
            if (deleteForm) {
                deleteForm.action = "{{ route('admin.rooms.delete', '__ROOM_ID__') }}".replace('__ROOM_ID__', roomId);
            }
        }
    });
</script>

<div class="max-w-7xl w-full mx-auto py-10 lg:px-8">
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $room->provider_name }} - Room {{ $room->room_number }}</h1>
                    <div class="mt-1 flex items-center">
                        <svg class="h-5 w-5 text-gray-400 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-gray-600">{{ $room->booking_address }}</p>
                    </div>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0 flex space-x-2">
                    <a href="#" class="btn-edit inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Edit</a>
                    <form action="#" method="POST" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this room?');">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 p-6">
            <div class="lg:col-span-3">
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-gray-100">
                    <img class="object-cover w-full h-full" src="{{ asset('storage/images/' . $room->displayImage) }}" alt="Main image of the room">
                </div>
            </div>
            <div class="lg:col-span-2">
                <h2 class="text-lg font-semibold text-gray-900">About this property</h2>
                <div class="mt-2 text-sm text-gray-600 space-y-4">
                    <p>{{ $room->description }}</p>
                </div>
            </div>
        </div>

        @php $facilities = $room->room_facilities ? explode(',', $room->room_facilities) : []; @endphp
        @if(count($facilities) > 0)
        <div class="p-6 border-t border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Room Facilities</h2>
            <div class="mt-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach ($facilities as $facility)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                        <img class="h-6 w-6 mr-3" src="{{ asset('storage/icons/' . trim($facility) . '.png') }}" alt="{{ trim($facility) }} icon" onerror="this.style.display='none'">
                        <span class="text-sm font-medium text-gray-700">{{ trim($facility) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
