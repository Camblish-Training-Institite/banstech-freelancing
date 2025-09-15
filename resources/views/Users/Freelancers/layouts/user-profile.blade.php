@extends('dashboards.freelancer.dashboard')

@section('body')
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
                <img class="h-32 w-32 rounded-full object-cover border-4 border-white" src="{{ $user->profile ? asset('storage/'.$user->profile->avatar) : 'https://ui-avatars.com/api/?name='. $user->name .'&background=random&size=128' }}" alt="Bobby Drake">
                <button class="absolute bottom-1 right-1 bg-white hover:bg-gray-200 text-gray-700 h-8 w-8 rounded-full flex items-center justify-center shadow-md transition-all duration-300">
                    <i class="fas fa-pencil-alt text-xs"></i>
                </button>
            </div>
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">Senior Laravel Developer</p>
                <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-2"></i>{{ $user->profile ? $user->profile->location : 'N/A' }}<button class="text-gray-500 hover:text-gray-800 ml-2"><i class="fas fa-pencil-alt"></i></button></p>
            </div>
            <button class="ml-auto bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                <i class="fas fa-edit mr-2"></i>Edit Profile
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Me -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">About Me</h3>
                        <button class="text-gray-500 hover:text-gray-800"><i class="fas fa-pencil-alt"></i></button>
                    </div>
                    <p class="text-gray-600">
                        {{ $user->profile ? $user->profile->bio : 'N/A' }}
                    </p>
                </div>

                <!-- Portfolio -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Portfolio</h3>
                        <button class="accent-purple hover:opacity-90 text-white font-semibold py-2 px-4 rounded-lg text-sm transition-all duration-300">
                            <i class="fas fa-plus mr-2"></i>Add New Item
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Portfolio Item 1 -->
                        @forelse ($user->portfolio as $item)
                            <div class="border rounded-lg overflow-hidden group relative">
                                <img src="https://placehold.co/600x400/E2E8F0/333333?text=Project+2" alt="Portfolio Item" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-800">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-600">{{ $item->description }}</p>
                                </div>
                                <div class="absolute top-2 right-2 flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-gray-200"><i class="fas fa-pencil-alt text-xs"></i></button>
                                    <button class="bg-white h-8 w-8 rounded-full flex items-center justify-center shadow-md hover:bg-red-500 hover:text-white"><i class="fas fa-trash text-xs"></i></button>
                                </div>
                            </div>
                        @empty
                            <p>No portfolio items</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                    <!-- Skills -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Skills</h3>
                        <button class="text-gray-500 hover:text-gray-800"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">PHP</span>
                        <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">Laravel</span>
                        <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">Vue.js</span>
                        <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">MySQL</span>
                        <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">API Development</span>
                    </div>
                </div>
                <!-- Qualifications -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Qualifications</h3>
                            <button class="text-gray-500 hover:text-gray-800"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start group">
                            <i class="fas fa-graduation-cap text-gray-400 mt-1 mr-4"></i>
                            <div>
                                <p class="font-semibold text-gray-800">B.Sc. in Computer Science</p>
                                <p class="text-sm text-gray-600">University of Cape Town</p>
                                <p class="text-xs text-gray-400">2012 - 2015</p>
                            </div>
                            <div class="ml-auto flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="text-gray-400 hover:text-gray-700"><i class="fas fa-pencil-alt text-xs"></i></button>
                                    <button class="text-gray-400 hover:text-red-500"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Certificates -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Certificates</h3>
                            <button class="text-gray-500 hover:text-gray-800"><i class="fas fa-plus-circle"></i></button>
                    </div>
                    <ul class="space-y-4">
                        <li class="flex items-start group">
                            <i class="fas fa-certificate text-gray-400 mt-1 mr-4"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Laravel Certified Developer</p>
                                <p class="text-sm text-gray-600">Laravel</p>
                                <p class="text-xs text-gray-400">Issued 2018</p>
                            </div>
                            <div class="ml-auto flex space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="text-gray-400 hover:text-gray-700"><i class="fas fa-pencil-alt text-xs"></i></button>
                                    <button class="text-gray-400 hover:text-red-500"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection