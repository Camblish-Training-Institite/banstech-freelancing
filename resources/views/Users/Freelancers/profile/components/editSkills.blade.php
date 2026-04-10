<div id="editSkillsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">

        
            <h2 class="text-xl font-bold mb-4">Edit Skills</h2>

            {{-- <form method="POST" action="{{ route('freelancer.profile.updateSkills') }}">
            @csrf
            @method('PATCH') --}}
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


                <input type="hidden" name="skills[]" id="required_skills"
                    value="{{ old('skills', is_array($job->skills ?? null) ? implode(', ', $job->skills) : $job->skills ?? '') }}">
                <!--  old('required_skills', $contest->required_skills)  -->

                <button id="submit-button" type="button"
                    class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>

                <button type="button" id="cancelSkillsBtn"
                    class="mt-4 ml-2 px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
            </div>
        {{-- </form> --}}
    </div>
</div>

<script>
    // This script for the facilities selector is preserved and slightly adapted
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('facility-input')) {
            const editSkillsBtn = document.getElementById('editSkillsBtn');
            const cancelSkillsBtn = document.getElementById('cancelSkillsBtn');
            const modal = document.getElementById('editSkillsModal');


            const inputField = document.getElementById('facility-input');
            const suggestionsList = document.getElementById('suggestions');
            const selectedContainer = document.getElementById('selected-facilities');
            const facilitiesHiddenInput = document.getElementById('required_skills');
            const submitButton = document.getElementById('submit-button');

            editSkillsBtn.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });

            cancelSkillsBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            });

            const allFacilities = [
                // Web & Software Development
                'HTML', 'CSS', 'JavaScript', 'PHP', 'Laravel', 'Python', 'Django', 'React', 'Vue.js',
                'Node.js',
                'Express.js', 'Ruby on Rails', 'Java', 'Spring Boot', 'C#', '.NET', 'SQL', 'MySQL',
                'PostgreSQL',
                'MongoDB', 'RESTful APIs', 'GraphQL', 'Docker', 'Kubernetes', 'AWS', 'Azure', 'Git',
                'GitHub',

                // Mobile Development
                'Android Development', 'iOS Development', 'Flutter', 'React Native', 'Swift', 'Kotlin',

                // Design & Creative
                'UI/UX Design', 'Figma', 'Adobe XD', 'Photoshop', 'Illustrator', 'Graphic Design',
                'Logo Design',
                'Brand Identity', 'Web Design', 'Print Design', 'Motion Graphics', 'After Effects',
                'Blender',
                '3D Modeling', 'Cinema 4D', 'Character Design',

                // Writing & Content
                'Content Writing', 'Copywriting', 'Technical Writing', 'Blog Writing', 'SEO Writing',
                'Ghostwriting',
                'Creative Writing', 'Proofreading', 'Editing', 'Resume Writing', 'Grant Writing',
                'Scriptwriting',

                // Digital Marketing
                'SEO', 'Social Media Marketing', 'Facebook Ads', 'Google Ads', 'Email Marketing',
                'Content Marketing',
                'Influencer Marketing', 'PPC Advertising', 'Affiliate Marketing', 'Market Research',
                'Google Analytics',

                // Video & Audio
                'Video Editing', 'Premiere Pro', 'Final Cut Pro', 'DaVinci Resolve', 'Animation',
                '2D Animation',
                '3D Animation', 'Voice Over', 'Audio Editing', 'Podcast Editing', 'Sound Design',
                'Music Production',

                // Business & Admin
                'Virtual Assistance', 'Data Entry', 'Excel', 'Power BI', 'QuickBooks', 'Bookkeeping',
                'Accounting',
                'Project Management', 'Scrum', 'Agile', 'Business Analysis', 'Financial Modeling',
                'HR Consulting',

                // AI & Data
                'Machine Learning', 'Deep Learning', 'Data Science', 'Python (Pandas, NumPy)', 'TensorFlow',
                'PyTorch',
                'Computer Vision', 'NLP (Natural Language Processing)', 'Chatbot Development',
                'AI Consulting',

                // Other Technical Skills
                'Blockchain', 'Smart Contracts', 'Solidity', 'Ethereum', 'Cybersecurity', 'Ethical Hacking',
                'Penetration Testing',
                'Network Administration', 'Linux', 'Bash Scripting',

                // Miscellaneous
                'Translation', 'Transcription', 'Legal Consulting', 'Life Coaching', 'Career Coaching',
                'Fitness Training',
                'Nutrition Consulting', 'Interior Design', 'Architectural Design', 'CAD Design',
            ];
            let selectedFacilities = facilitiesHiddenInput.value ? facilitiesHiddenInput.value.split(',') : [];

            const renderSelected = () => {
                selectedContainer.innerHTML = '';
                selectedFacilities.forEach(facility => {
                    const badge = document.createElement('span');
                    badge.className =
                        'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800';
                    badge.style =
                        'display: inline-flex; align-items:center; padding:0.325rem 0.825rem; border-radius: 1rem; background-color: rgb(224 231 255); color: rgb(55 48 163);'
                    badge.textContent = facility;

                    // Remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className =
                        'flex-shrink-0 ml-1.5 -mr-0.5 p-0.5 rounded-full inline-flex items-center justify-center text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none focus:bg-indigo-500 focus:text-white';
                    removeBtn.style =
                        "display: inline-flex;flex-shrink: 0; align-items: center; justify-content: center; margin-left: 0.375rem; margin-right: -0.125rem; padding: 0.125rem; border-radius: 9999px; color: #818cf8;"
                    removeBtn.innerHTML =
                        '<span class="sr-only">Remove</span><img width="10" height="10" src="https://img.icons8.com/ios-glyphs/30/6366f1/delete-sign.png" alt="delete-sign"/>';
                    removeBtn.onclick = () => {
                        selectedFacilities = selectedFacilities.filter(f => f !== facility);
                        facilitiesHiddenInput.value = JSON.stringify(selectedFacilities);
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
                const filtered = allFacilities.filter(f => f.toLowerCase().includes(query) && !
                    selectedFacilities.includes(f));
                if (filtered.length > 0) {
                    suggestionsList.classList.remove('hidden');
                    filtered.forEach(facility => {
                        const li = document.createElement('li');
                        li.className =
                            'cursor-default select-none relative py-2 px-4 text-gray-900 hover:bg-indigo-100';
                        li.textContent = facility;
                        li.onclick = () => {
                            selectedFacilities.push(facility);
                            facilitiesHiddenInput.value = selectedFacilities.join(',');
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

            inputField.addEventListener('input', updateSuggestions);
            document.addEventListener('click', (e) => {
                if (!suggestionsList.contains(e.target) && e.target !== inputField) {
                    suggestionsList.classList.add('hidden');
                }
            });

            // Prevent form submission on Enter key in facility input
            inputField.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const firstSuggestion = suggestionsList.querySelector('li');
                    if (firstSuggestion) {
                        firstSuggestion.click();
                    }
                }
            });

            // The original save button logic is now handled by the main form submit
            // so we remove the standalone save button's event listener to avoid conflicts.
            // We also hide the original save button if it exists.
            const oldSaveButton = document.getElementById('save-button');
            if (oldSaveButton) oldSaveButton.style.display = 'none';

            renderSelected();
        }
    });
</script>
