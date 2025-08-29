<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Project Files</h3>

    <!-- File Upload Form -->
    {{-- add action URL to form --}}
    <form action="" method="POST" enctype="multipart/form-data" class="mb-6 border-b pb-6">
        @csrf
        <div class="mb-4">
            <label for="file" class="block text-gray-700 text-sm font-bold mb-2">Upload New File</label>
            <input type="file" name="file" id="file" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('file') border-red-500 @enderror" required>
            @error('file')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex items-center justify-end mb-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Upload
            </button>
        </div>
    </form>

    <!-- Uploaded Files List -->
    <h4 class="text-lg font-bold text-gray-800 mb-4">Uploaded Files</h4>
    <ul>
        {{-- create a files table and model where project files can be stored --}}
        @forelse ($project->files as $file)
            <li class="border-b py-3 flex items-center justify-between">
                <div>
                    <a href="{{ $file->file_url }}" target="_blank" class="text-indigo-600 hover:underline">{{ $file->file_name }}</a>
                    <p class="text-sm text-gray-500">Uploaded by {{ $file->user->name }} on {{ $file->created_at->format('d M Y') }}</p>
                </div>
                <span class="text-sm text-gray-500">{{ $file->file_size }}</span>
            </li>
        @empty
            <li class="text-gray-500">No files have been uploaded yet.</li>
        @endforelse
        
    </ul>
</div>
