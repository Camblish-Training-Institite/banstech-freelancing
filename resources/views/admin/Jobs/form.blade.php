@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-4">
        <div class="px-6 py-5 border-b border-gray-200">
            <h1 class="text-2xl font-bold text-gray-800">{{ $action }} Job</h1>
            <p class="mt-1 text-sm text-gray-600">Fill in the details for the freelance job posting.</p>
        </div>

        <form method="POST" action="{{ $action === 'Create' ? route('admin.jobs.store') : route('admin.jobs.update', $job->id) }}" class="px-4 pb-4">
            @csrf
            @if($action === 'Edit')
                @method('PUT')
            @endif

            <div class="p-6 space-y-6">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Please correct the errors below:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Job Title</label>
                        <input type="text" name="title" id="title" value="{{ old('title') ?? $job->title }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="budget" class="block text-sm font-medium text-gray-700">Budget ($)</label>
                        <input type="number" name="budget" id="budget" value="{{ old('budget') ?? $job->budget }}" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" step="0.01">
                    </div>
                    <div>
                        <label for="job_type" class="block text-sm font-medium text-gray-700">Job Type</label>
                        <select name="job_type" id="job_type" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            <option value="online" @selected(old('job_type', $job->job_type) == 'online')>Online / Remote</option>
                            <option value="physical" @selected(old('job_type', $job->job_type) == 'physical')>Physical / On-site</option>
                        </select>
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @foreach(['open', 'in_progress', 'assigned', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" {{ (old('status', $job->status ?? 'open') == $status) ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" required class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id', $job->category_id) == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('description') ?? $job->description }}</textarea>
                    </div>
                </div>

                {{-- Skills Section --}}
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900">Required Skills</h3>
                    <p class="text-sm text-gray-500">Search and select the skills required for this job. These will be stored as JSON.</p>
                    <div class="mt-2">
                        <input type="text" id="skill-input" placeholder="Search for a skill (e.g. PHP, Graphic Design, Writing)..." class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                        <ul id="skill-suggestions" class="hidden mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg z-10"></ul>
                    </div>
                    <div id="selected-skills" class="mt-2 flex flex-wrap gap-2">
                        {{-- Selected skills will appear here as badges --}}
                    </div>
                    
                    {{-- Hidden input to store the comma-separated or JSON skills list --}}
                    <input type="hidden" name="skills" id="job_skills" value="{{ old('skills', is_array($job->skills) ? implode(',', $job->skills) : $job->skills) }}">
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end items-center space-x-4 rounded-lg">
                 <a href="{{ route('admin.jobs.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    {{ $action }} Job
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputField = document.getElementById('skill-input');
        const suggestionsList = document.getElementById('skill-suggestions');
        const selectedContainer = document.getElementById('selected-skills');
        const skillsHiddenInput = document.getElementById('job_skills');

        // List of common skills based on a freelancing platform context
        const allSkills = [
            'PHP', 'Laravel', 'JavaScript', 'Vue.js', 'React', 'Node.js', 'Python', 
            'Graphic Design', 'Logo Design', 'Content Writing', 'Copywriting', 
            'SEO', 'Digital Marketing', 'Social Media Management', 'UI/UX Design', 
            'Data Entry', 'Virtual Assistant', 'Mobile App Development', 'MySQL'
        ];

        // Initialize selected skills from the hidden input (handles old values or DB data)
        let selectedSkills = skillsHiddenInput.value ? skillsHiddenInput.value.split(',').filter(s => s !== "") : [];

        const renderSelected = () => {
            selectedContainer.innerHTML = '';
            selectedSkills.forEach(skill => {
                const badge = document.createElement('span');
                badge.className = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
                badge.style = 'display: inline-flex; align-items:center; padding:0.325rem 0.825rem; border-radius: 1rem; background-color: rgb(224 231 255); color: rgb(55 48 163);'
                badge.textContent = skill;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.style = "display: inline-flex; flex-shrink: 0; align-items: center; justify-content: center; margin-left: 0.375rem; color: #818cf8; border: none; background: none; cursor: pointer;";
                removeBtn.innerHTML = '<img width="12" height="12" src="https://img.icons8.com/ios-glyphs/30/6366f1/delete-sign.png" alt="remove"/>';
                
                removeBtn.onclick = () => {
                    selectedSkills = selectedSkills.filter(s => s !== skill);
                    skillsHiddenInput.value = selectedSkills.join(',');
                    renderSelected();
                };
                badge.appendChild(removeBtn);
                selectedContainer.appendChild(badge);
            });
        };

        const updateSuggestions = () => {
            const query = inputField.value.trim().toLowerCase();
            suggestionsList.innerHTML = '';
            if (query === '') {
                suggestionsList.classList.add('hidden');
                return;
            }
            const filtered = allSkills.filter(s => s.toLowerCase().includes(query) && !selectedSkills.includes(s));
            if (filtered.length > 0) {
                suggestionsList.classList.remove('hidden');
                filtered.forEach(skill => {
                    const li = document.createElement('li');
                    li.className = 'cursor-pointer select-none relative py-2 px-4 text-gray-900 hover:bg-indigo-100';
                    li.textContent = skill;
                    li.onclick = () => {
                        selectedSkills.push(skill);
                        skillsHiddenInput.value = selectedSkills.join(',');
                        renderSelected();
                        inputField.value = '';
                        suggestionsList.classList.add('hidden');
                    };
                    suggestionsList.appendChild(li);
                });
            } else {
                suggestionsList.classList.add('hidden');
            }
        };

        if (inputField) {
            inputField.addEventListener('input', updateSuggestions);
            
            // Close suggestions when clicking outside
            document.addEventListener('click', (e) => {
                if (!suggestionsList.contains(e.target) && e.target !== inputField) {
                    suggestionsList.classList.add('hidden');
                }
            });

            // Prevent form submission on Enter
            inputField.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const first = suggestionsList.querySelector('li');
                    if (first) first.click();
                }
            });

            renderSelected();
        }
    });
    </script>
@endsection

