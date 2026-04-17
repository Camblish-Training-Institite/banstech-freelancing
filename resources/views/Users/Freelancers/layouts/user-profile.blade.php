@extends('dashboards.freelancer.dashboard')

@section('body')

    @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    <!-- Main Content -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .accent-purple {
            background-color: #7A4D8B;
        }

        .accent-purple-text {
            color: #7A4D8B;
        }

        .accent-purple-border {
            border-color: #7A4D8B;
        }
    </style>

    @php
        $user = Auth::user();
    @endphp
    <main class="flex-1 overflow-y-auto p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Profile Management</h1>

        <!-- Profile Header -->
        <div class="bg-white w-full rounded-lg shadow-md p-6 mb-8 flex items-center mt-20">

            <div class="relative">
                <img class="h-32 w-32 rounded-full object-cover border-4 border-white"
                    src="{{ $user->profile ? asset('storage/' . $user->profile->avatar) : 'https://ui-avatars.com/api/?name=' . $user->name . '&background=random&size=128' }}"
                    alt="Bobby Drake">
                <button id="editAvatarBtn"
                    class="absolute bottom-1 right-1 bg-white hover:bg-gray-200 text-gray-700 h-8 w-8 rounded-full flex items-center justify-center shadow-md transition-all duration-300">
                    <i class="fas fa-pencil-alt text-xs"></i>
                </button>
                <input type="file" id="avatarInput" class="hidden" accept="image/*">
            </div>




            <script>
                const editAvatarBtn = document.getElementById('editAvatarBtn');
                const avatarInput = document.getElementById('avatarInput');

                editAvatarBtn.addEventListener('click', () => {
                    avatarInput.click();
                });

                avatarInput.addEventListener('change', (event) => {
                    const file = event.target.files[0];
                    if (file) {
                        // Show live preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            editAvatarBtn.previousElementSibling.src = e.target.result;
                        };
                        reader.readAsDataURL(file);

                        // Upload to Laravel
                        uploadAvatar(file);
                    }
                });

                function uploadAvatar(file) {
                    const formData = new FormData();
                    formData.append('avatar', file);

                    fetch("{{ route('freelancer.profile.update.avatar') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.avatar_url) {
                                // Update image with stored path (optional)
                                editAvatarBtn.previousElementSibling.src = data.avatar_url;
                            } else if (data.errors) {
                                alert("Error: " + Object.values(data.errors).join("\n"));
                            }
                        })
                        .catch(error => {
                            console.error("Upload error:", error);
                        });
                }
            </script>


            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->profile ? $user->profile->title : 'N/A' }}</p>
                <p class="text-sm text-gray-500 mt-1"><i
                        class="fas fa-map-marker-alt mr-2"></i>{{ $user->profile ? $user->profile->location : 'N/A' }}<button
                        id="editLocationBtn" class="text-gray-500 hover:text-gray-800 ml-2"><i
                            class="fas fa-pencil-alt"></i></button></p>
            </div>
            <button id="editProfileBtn"
                class="ml-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </button>
        </div>


        @include('Users.Freelancers.profile.components.editProfileHeader')


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Me -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">About Me</h3>
                        <button id="editAboutMeBtn" class="text-gray-500 hover:text-gray-800"><i
                                class="fas fa-pencil-alt"></i></button>
                    </div>
                    <p class="text-gray-600">
                        {{ $user->profile ? $user->profile->bio : 'N/A' }}
                    </p>
                </div>

                @include('Users.Freelancers.profile.components.editAboutMe')'



                {{--
            <!-- Portfolio -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Portfolio</h3>
                    <button
                        class="accent-purple hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                        <i class="fas fa-plus mr-2"></i>Add New Item
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Portfolio Item 1 -->
                    @forelse ($user->portfolio as $item)
                    <div class="border rounded-lg overflow-hidden group relative">
                        <img src="https://placehold.co/600x400/E2E8F0/333333?text=Project+2" alt="Portfolio Item"
                            class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h4 class="font-bold text-gray-800">{{ $item->title }}</h4>
                            <p class="text-sm text-gray-600">{{ $item->description }}</p>
                        </div>
                        <div
                            class="absolute top-2 right-2 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button
                                class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-gray-200"><i
                                    class="fas fa-pencil-alt text-xs"></i></button>
                            <button
                                class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-red-500 hover:text-white"><i
                                    class="fas fa-trash text-xs"></i></button>
                        </div>
                    </div>
                    @empty
                    <p>No portfolio items</p>
                    @endforelse
                </div>
            </div> --}}

                @include('Users.Freelancers.profile.components.editPortfolio')

                <!-- Address -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Address</h3>
                        <button id="editAddressBtn" class="text-gray-500 hover:text-gray-800"><i
                                class="fas fa-pencil-alt"></i></button>
                    </div>
                    <p class="text-gray-600">
                        Street: {{ $user->profile ? $user->profile->address : 'N/A' }}
                    </p>
                    <p class="text-gray-600 mt-2">
                        City: {{ $user->profile ? $user->profile->city : 'N/A' }}
                    </p>
                    <p class="text-gray-600 mt-2">
                        State: {{ $user->profile ? $user->profile->state : 'N/A' }}
                    </p>
                    <p class="text-gray-600 mt-2">
                        Zip Code: {{ $user->profile ? $user->profile->zip_code : 'N/A' }}
                    </p>
                    <p class="text-gray-600 mt-2">
                        Country: {{ $user->profile ? $user->profile->country : 'N/A' }}
                    </p>
                </div>

                @include('Users.Freelancers.profile.components.editAddress')
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Skills -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Skills</h3>
                        <button id="editSkillsBtn" class="text-gray-500 hover:text-gray-800"><i
                                class="fas fa-plus-circle"></i></button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        {{-- @foreach ($user->skills as $skill)
                            <span
                                class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm flex items-center space-x-2">
                                <span>{{ $skill->name }}</span>
                                <button class="text-gray-500 hover:text-gray-800"><i
                                        class="fas fa-times text-xs"></i></button>
                            </span>
                        @endforeach --}}

                        {{-- @if ($user->profile && $user->profile->skill->count())
                            @foreach ($user->profile->skills as $skill)
                                <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                                    {{ $skill->name }}
                                </span>
                            @endforeach
                        @else
                            <p class="text-gray-500">No skills added yet.</p>
                        @endif --}}



                    </div>
                </div>

                @include('Users.Freelancers.profile.components.editSkills')


                <!-- Qualifications -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Qualifications</h3>
                        <button type="button" id="editQualificationsBtn" class="text-gray-500 hover:text-gray-800"><i
                                class="fas fa-plus-circle"></i></button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start group">
                            <i class="fas fa-graduation-cap text-gray-400 mt-1 mr-4"></i>
                            <div>
                                    <p class="font-semibold text-gray-800">
                                        {{-- {{dd($user->qualificaiton)}} --}}
                                        Qualification:{{$user->qualificaiton ? $user->qualification->degree : 'N/A' }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        Institution: {{ $user->qualificaiton ? $user->qualification->institution : 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-400">
                                        Completed: {{ $user->qualificaiton ? $user->qualification->year_of_completion : 'N/A' }}
                                    </p>
                                
                            </div>
                            <div class="ml-auto flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="text-gray-400 hover:text-gray-700"><i
                                        class="fas fa-pencil-alt text-xs"></i></button>
                                <button class="text-gray-400 hover:text-red-500"><i
                                        class="fas fa-trash text-xs"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>

                @include('Users.Freelancers.profile.components.editQualifications')

                <!-- Certificates -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Certificates</h3>
                        <button id="editCertificatesBtn" class="text-gray-500 hover:text-gray-800"><i
                                class="fas fa-plus-circle"></i></button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start group">
                            <i class="fas fa-certificate text-gray-400 mt-1 mr-4"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Laravel Certified Developer</p>
                                <p class="text-sm text-gray-600">Laravel</p>
                                <p class="text-xs text-gray-400">Issued 2018</p>
                                <p class="text-xs text-gray-400">Expires 2023</p>
                                <p class="text-xs text-gray-400">Certificate ID: LAR-123456</p>
                            </div>
                            <div class="ml-auto flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="text-gray-400 hover:text-gray-700"><i
                                        class="fas fa-pencil-alt text-xs"></i></button>
                                <button class="text-gray-400 hover:text-red-500"><i
                                        class="fas fa-trash text-xs"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>

                @include('Users.Freelancers.profile.components.editCertificates')

            </div>
        </div>
    </main>
@endsection
