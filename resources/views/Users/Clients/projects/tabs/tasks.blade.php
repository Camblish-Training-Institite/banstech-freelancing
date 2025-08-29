<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Project Tasks</h3>

    <!-- Create Task Form -->
    {{-- add action URL for tasks form --}}
    <form action="{{ route('client.project.tabs.task', $project->id) }}" method="POST" enctype="multipart/form-data" class="mb-6 border-b pb-6">
        @csrf
        <div class="mb-4">
            <label for="task_description" class="block text-gray-700 text-sm font-bold mb-2">New Task</label>
            <div class="flex">
                <input type="text" name="title" id="title" class="shadow appearance-none border rounded-l w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" placeholder="e.g., Design the homepage mockup" required>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">
                    Add
                </button>
            </div>
             @error('description')
                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
            @enderror
        </div>
    </form>

    <!-- Task List -->
    <h4 class="text-lg font-bold text-gray-800 mb-4">Task List</h4>
    <ul>
        {{-- add tasks table and model for projects --}}
        @forelse ($project->tasks as $task)
            <li class="border-b py-3 flex items-center justify-between">
                <div>
                    <p class="text-gray-800">{{ $task->title }}</p>
                    <p class="text-sm text-gray-500">Status: {{ ucfirst($task->status) }} | Due: {{ $task->due_date ? $task->due_date->format('d M Y') : 'N/A' }}</p>
                </div>
                <span class="text-sm text-gray-500">Assigned to: {{ $task->user->name }}</span>
            </li>
        @empty
            <li class="text-gray-500">No tasks have been created yet.</li>
        @endforelse
    </ul>
</div>
