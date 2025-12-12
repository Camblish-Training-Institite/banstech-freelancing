@extends('Users.Clients.layouts.body.dashboard-body')

@section('body')
<h2 class="font-extrabold text-2xl mt-1">{{ isset($job) ? 'Edit Job' : 'Create Job' }}</h2>

<form action="{{ isset($job) ? route('jobs.update', $job) : route('jobs.store') }}" method="POST" id="job-create-form">
    @csrf 
    

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



            {{-- Job Type, Physical or  online --}}
            <div class="mb-6">
                <div>
                    <label for="job_type" class="block text-sm font-semibold text-gray-600 mb-2">physical job?</label>
                    <input type="hidden" name="job_type" value="online">
                    <input type="checkbox" name="job_type" id="is_physical_job"
                        class="p-1 border rounded border-gray-300 focus:outline-none focus:ring-blue-500"
                        value="physical" {{ old('job_type', $job->job_type ?? 'online') == 'physical' ? 'checked' : '' }} required>
                </div>

                <!-- Hidden Div (to show/hide) -->
                <div id="physical-job-fields" style="display: none;" class="space-y-2">
                    <div class="form-group">
                        <label for="job_address">Job Location Address</label>
                        <input type="text" name="job_address" class="w-full p-1  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter street address">
                    </div>
                    <div class="form-group">
                        <label>Please enter the maximum distance away we should show you proposals from (km)</label>
                        <input type="number" name="freelancer_radius" class="w-full p-1  border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Durban">
                    </div>
                </div>
            </div>



            {{-- Job Category --}}
            <div>
                <h3 class="text-lg font-medium text-gray-900">Job Category</h3>
                <p class="mt-2 text-sm text-gray-600">Select a category that best fits your job requirements.
                </p>
            </div>

            @php
                $categories = \App\Models\Category::all();
            @endphp

            <!-- Main Category Dropdown -->
            <div>
                <form action="" id="category_form" method="">
                    {{-- CSRF Token --}}
                    @csrf
                    <label for="mainCategory_id" class="text-sm font-medium text-gray-700">Main Category</label>
                    <select id="mainCategory_id" name="mainCategory_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled selected>Select a main category...</option>
                        
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

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

            {{-- Status
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
            </div> --}}


            Skills
            <div>
                <label for="skills" class="block text-sm font-semibold text-gray-600 mb-2">Project Skills</label>
                <input type="text" name="skills" id="skills"
                    class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter skills separated by commas"
                    value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}">
            </div>
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

</form>

<!-- tag script -->
@include('components.tag-script')


<!-- Script for physical job checkbox -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_physical_job');
        const physicalFields = document.getElementById('physical-job-fields');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                physicalFields.style.display = 'block';
            } else {
                physicalFields.style.display = 'none';
            }
        });
    });
</script>

{{-- Optional: Auto-focus on title input when page loads --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('job-create-form'); // ← Use the outer form
        const mainCategorySelect = document.getElementById('mainCategory_id');
        const subCategorySelect = document.getElementById('subCategory');

        // console.log(`Form:`, form);
        // console.log(`Main Category Select:`, mainCategorySelect);
        // console.log(`Sub Category Select:`, subCategorySelect);
        

        if (!form || !mainCategorySelect || !subCategorySelect) {
            console.error('Required elements not found!');
            return;
        }

        const fetchSubcategories = () => {
            const formData = new FormData();
            formData.append('mainCategory_id', mainCategorySelect.value);

            const params = new URLSearchParams(formData).toString();
            // console.log(`Fetching subcategories with params:`, params);

            fetch(`{{ route('client.jobs.subcategories') }}?${params}`)
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    subCategorySelect.innerHTML = '<option value="" disabled selected>First, select a main category...</option>';
                    data.subcategories.forEach(subcat => {
                        const option = document.createElement('option');
                        option.value = subcat.id;
                        option.textContent = subcat.name;
                        subCategorySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                });
        };

        mainCategorySelect.addEventListener('change', fetchSubcategories);
    });
</script>
@endsection