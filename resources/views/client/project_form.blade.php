@extends('layouts.app')

@section('content')

    <div class="main-container">
        <form method="{{ $method }}" action="{{ $actionURL }}" class="form" id="create_form" enctype="multipart/form-data">
            @csrf

            <div class="title-container">
                <h2 class="form-title">Create job</h2>
            </div>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-content">
                    <div class="content-container">
                        <div class="flex flex-col w-full items-start">
                            <label class="frm-label" for="title">Job title: </label>
                            <input 
                                type="text" 
                                name="title" 
                                id="title"
                                value="{{old('title') ?? $job->title}}"
                                class="frm-input"
                                required>
                        </div>
                    </div>

                    <div class="content-container">
                        <div class="flex flex-col w-full items-start">
                            <label class="frm-label" for="budget">Job Description: </label>
                            <textarea name="description" id="description" class="frm-txtArea" cols="30" rows="10">
                                {{old('description') ?? $job->description}}
                            </textarea>
                        </div>
                    </div>

                    <div class="content-container" style="flex-direction:column;">
                        <div class="flex flex-col w-full items-start">
                            <label class="frm-label" for="deadline">job Completion Date: </label>
                            <input 
                                style="margin-bottom: 1.5rem;"
                                type="date" 
                                name="deadline" 
                                id="deadline"
                                value="{{old('deadline') ?? $job->deadline}}"
                                class="frm-input"
                                required
                            >
                        </div>

                        <div class="flex flex-col w-full items-start">
                            <label class="frm-label" for="budget">job Budget: </label>
                            <input 
                                style="margin-bottom: 1.5rem;"
                                type="text" 
                                name="budget" 
                                id="budget"
                                {{-- value="{{old('budget') ?? $job->budget}}" --}}
                                class="frm-input"
                                required
                            >
                        </div>
                    </div>

                    <div class="content-container">
                        <div class="flex flex-col w-full items-start">
                            <h2>Desired Skills</h2>

                            <!-- Selected Facilities -->
                            <div id="selected-skills" class="selected-skills">
                                <ul id="selected-list"></ul>
                            </div>

                            <!-- Input Field -->
                            <div class="input-group">
                                <input type="text" id="skill-input" placeholder="Enter facility name..." />
                                <ul id="suggestions" class="dropdown-list"></ul>
                            </div>

                            <input type="hidden" name="job_skills" id="job_skills" value="">

                            <!-- Save Button -->
                            <button id="save-button">Save</button>
                        </div>
                    </div>  
                </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputField = document.getElementById('facility-input');
            const suggestionsList = document.getElementById('suggestions');
            const selectedList = document.getElementById('selected-list');
            const saveButton = document.getElementById('save-button');

            // Sample list of facilities
            const allFacilities = [
                'Job Creation',
                'Skill Development',
                'Career Guidance',
                'Interview Preparation',
                'Resume Writing',
                'Cover Letter Writing',
                'Networking Skills',
                'Time Management',
                'Problem Solving',
                'Communication Skills',
                'Teamwork',
                'Leadership',
                'Adaptability',
                'Critical Thinking',
                'job Management',
                'Technical Skills',
                'Soft Skills',
                'Professionalism',
                'Work Ethic',
                'Creativity',
                'Analytical Skills',
                'Negotiation',
                'Public Speaking',
                'Attention to Detail',
                'Multitasking',
                'Stress Management',
                'Continuous Learning',
                'Client Relationship Management',
                'Data Analysis',
                'Digital Marketing',
                'SEO Optimization',
                'Content Creation',
                'Graphic Design',
                'Programming',
                'Database Management',
                'Customer Service',
                'Sales Techniques',
                'HR Management',
                'Financial Planning',
                'Legal Compliance',
                'Environmental Awareness',
                'Sustainability Practices',
            ];

            // Function to filter facilities based on input
            function filterFacilities(query) {
                return allFacilities.filter(facility =>
                    facility.toLowerCase().includes(query.toLowerCase())
                );
            }

            // Function to update suggestions
            function updateSuggestions() {
                const query = inputField.value.trim();
                const filteredFacilities = filterFacilities(query);

                // Clear previous suggestions
                suggestionsList.innerHTML = '';

                if (query === '') {
                    suggestionsList.style.display = 'none';
                    return;
                }

                // Display suggestions
                suggestionsList.style.display = 'block';
                filteredFacilities.forEach(facility => {
                    const li = document.createElement('li');
                    li.textContent = facility;
                    li.addEventListener('click', () => {
                        addSelectedFacility(facility);
                        inputField.value = '';
                        suggestionsList.style.display = 'none';
                    });
                    suggestionsList.appendChild(li);
                });
            }

            // Function to add selected facility
            function addSelectedFacility(facility) {
                const existingFacilities = Array.from(selectedList.children).map(
                    item => item.textContent
                );

                if (!existingFacilities.includes(facility)) {
                    const li = document.createElement('li');
                    li.textContent = facility;
                    selectedList.appendChild(li);
                }
            }

            // Event listeners
            inputField.addEventListener('input', updateSuggestions);
            inputField.addEventListener('focus', () => {
                updateSuggestions();
            });

            saveButton.addEventListener('click', () => {
                const selectedFacilities = Array.from(selectedList.children).map(
                    item => item.textContent
                );
                const facilitiesString = selectedFacilities.join(',');

                // Update the hidden input field
                document.getElementById('job_skills').value = facilitiesString;

                });
            });
    </script>

    @push('styles')
        <link rel="stylesheet" href="{{ asset('pages_css/forms.css') }}">
    @endpush
            
@endsection