{{-- resources/views/provider/showImage.blade.php --}}
@extends('layouts.admin')

@section('content')
{{-- The original script is preserved to maintain functionality --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const url = window.location.href;
        const photoidMatch = url.match(/\/admin\/providers\/\d+\/images\/(\d+)/);
        if (photoidMatch && photoidMatch[1]) {
            let photoId = parseInt(photoidMatch[1], 10);
            let providerId = {{ $providerId }};

            const editButtonLink = document.querySelector('.btn-edit');
            if (editButtonLink) {
                editButtonLink.href = `/admin/providers/${providerId}/images/edit/${photoId}`;
            }

            const deleteForm = document.querySelector('.form-delete');
            if (deleteForm) {
                deleteForm.action = `/admin/providers/${providerId}/images/delete/${photoId}`;
            }
        }
    });
</script>

<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="mb-4">
        <a href="{{ route('image.index', $providerId) }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Gallery
        </a>
    </div>
    
    @if(session('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between -mt-2 -ml-4">
                <div class="mt-2 ml-4">
                    <h1 class="text-2xl font-bold text-gray-800">Image Details</h1>
                    <p class="mt-1 text-sm text-gray-600">Image ID: {{ $photo->id }}</p>
                </div>
                <div class="mt-4 ml-4 flex-shrink-0 flex space-x-2">
                    <a href="#" class="btn-edit inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Edit</a>
                    <form action="#" method="POST" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="return confirm('Are you sure you want to delete this image?');">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="p-6 flex justify-center bg-gray-50">
            <img src="{{ asset('storage/images/'. $photo->image) }}" alt="Image ID {{ $photo->id }}" class="max-w-4xl w-full rounded-lg shadow-md">
        </div>
    </div>
</div>
@endsection
