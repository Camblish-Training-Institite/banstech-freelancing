@extends('Users.Clients.layouts.body.dashboard-body')

@section('body')
@php
    $selectedCategoryId = old('mainCategory_id', $job->category_id ?? null);
    $selectedSubcategoryId = old('subCategory', $job->subcategory_id ?? null);
    $formSubCategories = $subCategories ?? collect();

    if ($selectedCategoryId && $formSubCategories->isEmpty()) {
        $formSubCategories = \App\Models\SubCategory::where('parent_id', $selectedCategoryId)
            ->orderBy('name')
            ->get();
    }
@endphp

<h2 class="font-extrabold text-2xl mt-1">{{ isset($job) ? 'Edit Job' : 'Create Job' }}</h2>

<form action="{{ isset($job) ? route('client.jobs.update', $job) : route('client.jobs.store') }}" method="POST" id="job-create-form">
    @csrf
    @isset($job)
    @method('PUT')
    @endisset

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
            <div>
                <label for="title" class="block text-sm font-semibold text-gray-600 mb-2">Project Title</label>
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    value="{{ old('title', $job->title ?? '') }}"
                    required
                >
            </div>

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

            <div class="mb-6">
                <div>
                    <label for="job_type" class="block text-sm font-semibold text-gray-600 mb-2">physical job?</label>
                    <input type="hidden" name="job_type" value="online">
                    <input
                        type="checkbox"
                        name="job_type"
                        id="is_physical_job"
                        class="p-1 border rounded border-gray-300 focus:outline-none focus:ring-blue-500"
                        value="physical"
                        {{ old('job_type', $job->job_type ?? 'online') == 'physical' ? 'checked' : '' }}
                    >
                </div>

                <div id="physical-job-fields" style="display: none;" class="space-y-2">
                    <div class="form-group">
                        <label for="job_address">Job Location Address</label>
                        <input
                            type="text"
                            name="job_address"
                            id="job_address"
                            class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Enter street address"
                            value="{{ old('job_address') }}"
                        >
                    </div>
                    <div class="form-group">
                        <label for="freelancer_radius">Please enter the maximum distance away we should show you proposals from (km)</label>
                        <input
                            type="number"
                            name="freelancer_radius"
                            id="freelancer_radius"
                            class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="e.g., 25"
                            value="{{ old('freelancer_radius', $job->freelancer_radius ?? '') }}"
                        >
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900">Job Category</h3>
                <p class="mt-2 text-sm text-gray-600">Select a category that best fits your job requirements.</p>
            </div>

            <div>
                <label for="mainCategory_id" class="text-sm font-medium text-gray-700">Main Category</label>
                <select id="mainCategory_id" name="mainCategory_id" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a main category...</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) $selectedCategoryId === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('mainCategory_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="custom_category" class="text-sm font-medium text-gray-700">Or add a new category</label>
                <input
                    type="text"
                    id="custom_category"
                    name="custom_category"
                    class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Type a category if it is not listed"
                    value="{{ old('custom_category') }}"
                >
                @error('custom_category')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subCategory" class="text-sm font-medium text-gray-700">Subcategory</label>
                <select id="subCategory" name="subCategory" class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a subcategory...</option>
                    @foreach($formSubCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" @selected((string) $selectedSubcategoryId === (string) $subCategory->id)>{{ $subCategory->name }}</option>
                    @endforeach
                </select>
                @error('subCategory')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="custom_subcategory" class="text-sm font-medium text-gray-700">Or add a new subcategory</label>
                <input
                    type="text"
                    id="custom_subcategory"
                    name="custom_subcategory"
                    class="mt-1 block w-full px-4 py-3 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Type a subcategory if it is not listed"
                    value="{{ old('custom_subcategory') }}"
                >
                @error('custom_subcategory')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="deadline" class="block text-sm font-semibold text-gray-600 mb-2">Project Completion Date</label>
                    <input
                        type="date"
                        name="deadline"
                        id="deadline"
                        class="w-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="{{ old('deadline', isset($job) && $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('Y-m-d') : '') }}"
                    >
                </div>

                <div>
                    <label for="budget" class="block text-sm font-semibold text-gray-600 mb-2">Project Budget</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">ZAR</span>
                        <input
                            type="number"
                            name="budget"
                            id="budget"
                            class="w-full pl-12 border border-graed-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('budget', $job->budget ?? '') }}"
                            required
                        >
                    </div>
                </div>
            </div>

            Skills
            <div>
                <label for="skills" class="block text-sm font-semibold text-gray-600 mb-2">Project Skills</label>
                <input
                    type="text"
                    name="skills"
                    id="skills"
                    class="w-full p-1 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter skills separated by commas"
                    value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}"
                >
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900">Facilities</h3>
                <p class="text-sm text-gray-500">Select the facilities available in this room.</p>
                <div class="mt-2">
                    <input type="text" id="facility-input" placeholder="Search for a facility..." class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    <ul id="suggestions" class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
                </div>
                <div id="selected-facilities" class="mt-2 space-x-2 space-y-2"></div>
                <input
                    type="hidden"
                    name="skills"
                    id="required_skills"
                    value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}"
                >
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success p-1 text-white hover:bg-green-600 transition duration-300">
                {{ isset($job) ? 'Update Project' : 'Create Project' }}
            </button>
        </div>
    </div>
</form>

@include('components.tag-script')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkbox = document.getElementById('is_physical_job');
        const physicalFields = document.getElementById('physical-job-fields');

        const syncPhysicalFields = () => {
            physicalFields.style.display = checkbox.checked ? 'block' : 'none';
        };

        checkbox.addEventListener('change', syncPhysicalFields);
        syncPhysicalFields();
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainCategorySelect = document.getElementById('mainCategory_id');
        const subCategorySelect = document.getElementById('subCategory');
        const customCategoryInput = document.getElementById('custom_category');
        const customSubcategoryInput = document.getElementById('custom_subcategory');
        const selectedSubcategoryId = @json($selectedSubcategoryId);

        if (!mainCategorySelect || !subCategorySelect || !customCategoryInput || !customSubcategoryInput) {
            return;
        }

        const resetSubcategories = () => {
            subCategorySelect.innerHTML = '<option value="">Select a subcategory...</option>';
        };

        const fetchSubcategories = () => {
            if (!mainCategorySelect.value) {
                resetSubcategories();
                return;
            }

            const formData = new FormData();
            formData.append('mainCategory_id', mainCategorySelect.value);

            fetch(`{{ route('client.jobs.subcategories') }}?${new URLSearchParams(formData).toString()}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    return response.json();
                })
                .then(data => {
                    resetSubcategories();

                    data.subcategories.forEach(subcat => {
                        const option = document.createElement('option');
                        option.value = subcat.id;
                        option.textContent = subcat.name;

                        if (String(selectedSubcategoryId) === String(subcat.id)) {
                            option.selected = true;
                        }

                        subCategorySelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching subcategories:', error);
                });
        };

        mainCategorySelect.addEventListener('change', function () {
            if (this.value) {
                customCategoryInput.value = '';
            }

            customSubcategoryInput.value = '';
            fetchSubcategories();
        });

        customCategoryInput.addEventListener('input', function () {
            if (this.value.trim() !== '') {
                mainCategorySelect.value = '';
                resetSubcategories();
            }
        });

        customSubcategoryInput.addEventListener('input', function () {
            if (this.value.trim() !== '') {
                subCategorySelect.value = '';
            }
        });

        if (mainCategorySelect.value) {
            fetchSubcategories();
        }
    });
</script>
@endsection
