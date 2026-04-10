{{-- resources/views/provider/viewImages.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">Image Gallery</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage images for {{ $provider->provider_name }}.</p>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0">
                    <a href="{{ route('image.create', $provider->id) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Add New Image
                    </a>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if(count($photos) > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @foreach ($photos as $photo)
                        <a href="{{ route('image.show', [$provider->id, $photo->id]) }}" class="group block relative">
                            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-lg bg-gray-200">
                                <img src="{{ asset('storage/images/'. $photo->image) }}" alt="Photo ID {{ $photo->id }}" class="w-full h-full object-center object-cover group-hover:opacity-75 transition-opacity">
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <p class="text-white font-bold">View Details</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <h3 class="text-lg font-medium text-gray-900">No Images Found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by uploading your first image.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
