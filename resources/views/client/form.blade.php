 @if ($errors->any())
 <div class="bg-red-100 text-red-700 p-4 rounded mb-6" >
       <ul class="list-disc pl-5 space-y-1">
         @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
         @endforeach
       </ul> 
    </div>
  @endif  

<div class="bg-white p-8 rounded-xl shadow-lg w-full max-w mx-auto">

        <div class="space-y-6">
            {{-- Project Title --}}
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-600 mb-2">Project Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="w-full p-1  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('title', $job->title ?? '') }}"
                    required
                >
            </div>

            {{-- Project Description --}}
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-600 mb-2">Project Description</label>
                <textarea
                    name="description"
                    id="description"
                    rows="5"
                    class="w-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >{{ old('description', $job->description ?? '') }}</textarea>
            </div>

            {{-- Grid Layout for Deadline and Budget --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Deadline --}}
                <div>
                    <label for="deadline" class="block text-sm font-semibold text-gray-600 mb-2">Project Completion Date</label>
                    <input
                        type="date"
                        name="deadline"
                        id="deadline"
                        class="w-full  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('deadline', isset($job) && $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : '') }}"
                    >
                </div>

                {{-- Budget --}}
                <div>
                    <label for="budget" class="block text-sm font-semibold text-gray-600 mb-2">Project Budget</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">ZAR</span>
                        <input
                            type="number"
                            name="budget"
                            id="budget"
                            class="w-full  pl-12 border border-graed-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('budget', $job->budget ?? '') }}"
                            required
                        >
                    </div>
                </div>
            </div>
           
            {{-- Status --}}
            <div class="mt-6">
                <label for="status" class="block text-sm font-semibold text-gray-600 mb-2">Project Status</label>
                <select
                    name="status"
                    id="status"
                    class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="open" {{ old('status', $job->status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="in_progress" {{ old('status', $job->status ?? '') === 'in_progress' ? 'selected' : '' }}>In_progress</option>
                    <option value="assigned" {{ old('status', $job->status ?? '') === 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="completed" {{ old('status', $job->status ?? '') === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ old('status', $job->status ?? '') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>


            {{-- Skills --}}
            {{-- <div>
                <label for="skills" class="block text-sm font-semibold text-gray-600 mb-2">Project Skills</label>
                <input
                    type="text"
                    name="skills"
                    id="skills"
                    class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter skills separated by commas"
                    value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}"
                >
            </div> --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900">Facilities</h3>
                <p class="text-sm text-gray-500">Select the facilities available in this room.</p>
                <div class="mt-2">
                    <input type="text" id="facility-input" placeholder="Search for a facility..." class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <ul id="suggestions" class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
                </div>
                <div id="selected-facilities" class="mt-2 space-x-2 space-y-2">
                    {{-- Selected facilities will appear here --}}
                </div>
                <input type="hidden" name="skills" id="required_skills" value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}"> <!--  old('required_skills', $contest->required_skills)  -->
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="mt-4">
            <button
                type="submit"
                class="btn btn-success p-1 text-white  hover:bg-green-600 
                transition duration-300">
                {{ isset($job) ? 'Update Project' : 'Create Project' }}
            </button>
        </div>
    
</div>

<!-- tag script -->
@include('components.tag-script')

{{-- Optional: Auto-focus on title input when page loads --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('title').focus();
    });

</script>
