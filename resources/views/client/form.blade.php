@if ($errors->any())
<div class="bg-red-100 text-red-700 p-4 rounded mb-6">
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
            <input type="text" name="title" id="title"
                class="w-full p-1  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                value="{{ old('title', $job->title ?? '') }}" required>
        </div>



        {{-- Project Description --}}
        <div>
            <label for="description" class="block text-sm font-semibold text-gray-600 mb-2">Project Description</label>
            <textarea name="description" id="description" rows="5"
                class="w-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required>{{ old('description', $job->description ?? '') }}</textarea>
        </div>

        {{-- Job Category --}}
        <div>
            <h3 class="text-lg font-medium text-gray-900">Job Category</h3>
            <p class="mt-2 text-sm text-gray-600">Select a category that best fits your job requirements.
            </p>
        </div>

        <form action="" id="category_form" method="">
            {{-- CSRF Token --}}
            @csrf


            @php
                $categories = \App\Models\Category::all();
            @endphp

            <!-- Main Category Dropdown -->
            <div>
                <label for="mainCategory_id" class="text-sm font-medium text-gray-700">Main Category</label>
                <select id="mainCategory_id" name="mainCategory_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled selected>Select a main category...</option>
                    
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <!-- Subcategory Dropdown -->
        <div>
            <label for="subCategory" class="text-sm font-medium text-gray-700">Subcategory</label>
            <select id="subCategory" name="subCategory" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="" disabled selected>First, select a main category...</option>

                @if ($subCategories)
                    @foreach($subCategories as $subCategory)
                        <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>



        {{-- Grid Layout for Deadline and Budget --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Deadline --}}
            <div>
                <label for="deadline" class="block text-sm font-semibold text-gray-600 mb-2">Project Completion
                    Date</label>
                <input type="date" name="deadline" id="deadline"
                    class="w-full  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('deadline', isset($job) && $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : '') }}">
            </div>

            {{-- Budget --}}
            <div>
                <label for="budget" class="block text-sm font-semibold text-gray-600 mb-2">Project Budget</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">ZAR</span>
                    <input type="number" name="budget" id="budget"
                        class="w-full  pl-12 border border-graed-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('budget', $job->budget ?? '') }}" required>
                </div>
            </div>
        </div>

        {{-- Status --}}
        <div class="mt-6">
            <label for="status" class="block text-sm font-semibold text-gray-600 mb-2">Project Status</label>
            <select name="status" id="status"
                class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <option value="open" {{ old('status', $job->status ?? '') === 'open' ? 'selected' : '' }}>Open</option>
                <option value="in_progress" {{ old('status', $job->status ?? '') === 'in_progress' ? 'selected' : ''
                    }}>In_progress</option>
                <option value="assigned" {{ old('status', $job->status ?? '') === 'assigned' ? 'selected' : ''
                    }}>Assigned</option>
                <option value="completed" {{ old('status', $job->status ?? '') === 'completed' ? 'selected' : ''
                    }}>Completed</option>
                <option value="cancelled" {{ old('status', $job->status ?? '') === 'cancelled' ? 'selected' : ''
                    }}>Cancelled</option>
            </select>
        </div>


        {{-- Skills --}}
        {{-- <div>
            <label for="skills" class="block text-sm font-semibold text-gray-600 mb-2">Project Skills</label>
            <input type="text" name="skills" id="skills"
                class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Enter skills separated by commas"
                value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}">
        </div> --}}
        <div>
            <h3 class="text-lg font-medium text-gray-900">Facilities</h3>
            <p class="text-sm text-gray-500">Select the facilities available in this room.</p>
            <div class="mt-2">
                <input type="text" id="facility-input" placeholder="Search for a facility..."
                    class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                <ul id="suggestions"
                    class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
            </div>
            <div id="selected-facilities" class="mt-2 space-x-2 space-y-2">
                {{-- Selected facilities will appear here --}}
            </div>
            <input type="hidden" name="skills" id="required_skills"
                value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}">
            <!--  old('required_skills', $contest->required_skills)  -->
        </div>
    </div>

    {{-- Submit Button --}}
    <div class="mt-4">
        <button type="submit" class="btn btn-success p-1 text-white  hover:bg-green-600 
                transition duration-300">
            {{ isset($job) ? 'Update Project' : 'Create Project' }}
        </button>
    </div>

</div>

<!-- tag script -->
@include('components.tag-script')

{{-- Optional: Auto-focus on title input when page loads --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const myform = document.getElementById('category_form');
        const mainCategorySelect = document.getElementById('mainCategory_id');
        const subCategorySelect = document.getElementById('subCategory'); // ← Fixed ID
        console.log(`The form select element:`, myform);
        console.log(`The mainCategory select element:`, mainCategorySelect);
        console.log(`The subcategory select element:`, subCategorySelect);

        // Safety check: if elements don't exist, stop execution
        if (!myform || !mainCategorySelect || !subCategorySelect) {
            console.error('One or more required elements not found!');
            return;
        }

        console.log(`The value is ${mainCategorySelect.value}`);

        const fetchSubcategories = () => {
            // Only send the mainCategory_id
            const formData = new FormData(myform);
            formData.append('mainCategory_id', mainCategorySelect.value);

            const params = new URLSearchParams(formData).toString();

            const url = `{{ route('client.jobs.subcategories') }}?${params}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Subcategories received:', data);

                    // Clear existing options
                    subCategorySelect.innerHTML = '<option value="" disabled selected>First, select a main category...</option>';

                    // Populate subcategories
                    if (data.subcategories && Array.isArray(data.subcategories)) {
                        data.subcategories.forEach(subcat => {
                            const option = document.createElement('option');
                            option.value = subcat.id;
                            option.textContent = subcat.name; // Adjust based on model
                            subCategorySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                });
        };

        // Listen for changes on main category
        mainCategorySelect.addEventListener('change', (event) => {
            fetchSubcategories();
        });

        // Optional: Submit handling
        myform.addEventListener('submit', (event) => {
            event.preventDefault();
            fetchSubcategories();
        });
    });
</script>
