<div class="p-4 border-b border-gray-200 hover:bg-gray-50">
    <p class="font-medium">{{ $notification->data['message'] }}</p>
    <p class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
    <a href="{{ $notification->data['url'] }}" class="text-blue-600 text-sm hover:underline">View Project</a>
</div>