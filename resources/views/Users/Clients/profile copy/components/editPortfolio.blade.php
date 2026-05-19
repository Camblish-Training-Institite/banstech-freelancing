               <!-- Portfolio -->
<div class="bg-white rounded-lg shadow-md p-6" x-data="{ modalOpen: false }">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold text-gray-800">Portfolio</h3>
        <button 
            @click="modalOpen = true"
            class="accent-purple hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
            <i class="fas fa-plus mr-2"></i>Add New Item
        </button>
    </div>

    <!-- Modal Overlay -->
    <div 
        x-show="modalOpen"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.away="modalOpen = false">

        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-xl p-6 animate-fadeIn" style="width:60rem">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Add New Portfolio Item</h3>
            
            <form action="{{ route('profile.portfolio.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title *</label>
                    <input 
                        type="text" 
                        name="title" 
                        id="title" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        required
                        placeholder="Enter project title"
                    >
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        rows="3"
                        placeholder="Describe your project..."
                    ></textarea>
                </div>

                <div class="mb-4">
                    <label for="imageURL" class="block text-sm font-medium text-gray-700 mb-1">Image URL (optional)</label>
                    <input 
                        type="url" 
                        name="imageURL" 
                        id="imageURL" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="https://example.com/image.jpg"
                    >
                </div>

                <div class="mb-6">
                    <label for="file_url" class="block text-sm font-medium text-gray-700 mb-1">File URL (optional)</label>
                    <input 
                        type="url" 
                        name="file_url" 
                        id="file_url" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                        placeholder="https://example.com/document.pdf"
                    >
                </div>

                <div class="flex justify-end space-x-2">

                    <button 
                        type="button" 
                        @click="modalOpen = false"
                        class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition">
                        Cancel
                    </button>

                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Add Item
                    </button>
                    
                </div>
            </form>
        </div>
    </div>

    <!-- Portfolio Items Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($user->portfolio as $item)
            <div class="border rounded-lg overflow-hidden group relative">
                <img src="{{ $item->imageURL ?: 'https://placehold.co/600x400/E2E8F0/333333?text=Project+2' }}" alt="{{ $item->title }}" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h4 class="font-bold text-gray-800">{{ $item->title }}</h4>
                    <p class="text-sm text-gray-600">{{ $item->description }}</p>
                </div>
                <div class="absolute top-2 right-2 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-gray-200"><i class="fas fa-pencil-alt text-xs"></i></button>
                    <button class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-red-500 hover:text-white"><i class="fas fa-trash text-xs"></i></button>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-2 text-center py-8">No portfolio items yet.</p>
        @endforelse
    </div>
</div>