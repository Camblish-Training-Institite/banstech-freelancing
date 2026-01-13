{{-- resources/views/admin/providers.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">{{ $pagetitle }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage all property providers on the platform.</p>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0">
                    <a href="{{ route('providers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add New Provider
                    </a>
                </div>
            </div>
        </div>

        {{-- Session Messages --}}
        @if(session('success'))
            <div class="m-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col">
            <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                    <div class="shadow overflow-hidden border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 hidden md:flex flex-col">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Provider</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Images</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($providers as $provider)
                                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="window.location.href='{{ route('providers.single', $provider->id) }}'">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $provider->provider_name }}</div>
                                            <div class="text-sm text-gray-500">ID: {{ $provider->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-sm truncate" title="{{ $provider->booking_address }}">
                                            {{ $provider->booking_address }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            @if ($provider->phone)
                                                {{ $provider->phone }}
                                            @else
                                                <a href="{{ route('admin.contact.create', $provider->id) }}" class="text-indigo-600 hover:text-indigo-900" onclick="event.stopPropagation();">Add Contact</a>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('image.index', $provider->id) }}" class="text-indigo-600 hover:text-indigo-900" onclick="event.stopPropagation();">View Gallery</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">No providers found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        
                        {{-- Card layout for mobile screens --}}
                        <div class="grid grid-cols-1 gap-4 md:hidden px-4 pt-2">
                             @forelse ($providers as $provider)
                                <div class="bg-white p-4 rounded-lg border shadow-sm space-y-3 cursor-pointer" onclick="window.location.href='{{ route('providers.single', $provider->id) }}'">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $provider->provider_name }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $provider->id }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Address</p>
                                        <p class="text-sm text-gray-800 truncate">{{ $provider->booking_address }}</p>
                                    </div>
                                    <div class="flex justify-between items-center pt-2">
                                         <div>
                                            <p class="text-xs text-gray-500">Contact</p>
                                            <p class="text-sm text-gray-800">
                                                 @if ($provider->phone)
                                                    {{ $provider->phone }}
                                                @else
                                                    <a href="{{ route('admin.contact.create', $provider->id) }}" class="text-indigo-600 hover:text-indigo-900" onclick="event.stopPropagation();">Add Contact</a>
                                                @endif
                                            </p>
                                        </div>
                                        <a href="{{ route('image.index', $provider->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium" onclick="event.stopPropagation();">View Gallery</a>
                                    </div>
                                </div>
                            @empty
                                <div class="py-4 px-6 text-center text-gray-500">
                                    No providers found.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
