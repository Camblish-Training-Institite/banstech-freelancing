@extends('dashboards.client.dashboard')

@section('body')
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

    <div class="flex w-full justify-start">
        <a href="{{ url()->previous() }}" class="back-link">← Back</a>
    </div>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-8">

        <!-- Profile Header -->
        <div class="bg-white w-full rounded-lg shadow-md p-6 mb-8 flex items-center relative">
            <img class="h-32 w-32 rounded-full object-cover border-4 border-white" src="{{$profile ? asset('storage/'. $freelancer->profile->avatar) : 'https://ui-avatars.com/api/?name=' . $freelancer->name . '&background=random&size=128'}}"  alt="{{$freelancer->name}}">
            <div class="ml-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $freelancer->name }}</h2>
                <p class="text-gray-600">Senior Laravel Developer</p>
                <p class="text-sm text-gray-500 mt-1"><i class="fas fa-map-marker-alt mr-2"></i>Johannesburg, South Africa • Member since {{ $freelancer->created_at->diffForHumans() }}</p>

            </div>
            <div class="ml-auto flex space-x-4">
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg text-sm transition-all duration-300">
                    <i class="fas fa-envelope mr-2"></i>Message
                </button>
                <button class="accent-purple hover:opacity-90 text-white font-bold py-2 px-6 rounded-lg text-sm transition-all duration-300">
                    Hire Me
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- About Me -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">About Me</h3>
                    <p class="text-gray-600">
                        {{ $freelancer->profile ? $freelancer->profile->bio : 'No bio found' }}
                    </p>
                </div>

                @php
                    $projects = $freelancer->contractsAsFreelancer ? $freelancer->contractsAsFreelancer : [];
                @endphp
                <!-- Portfolio -->
                <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Portfolio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse ($projects as $project)
                            <div class="border rounded-lg overflow-hidden">
                                <img src="https://placehold.co/600x400/E2E8F0/333333?text={{$project->job->title}}" alt="Portfolio Item" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <div class="flex w-full space-x-2 items-center justify-between mb-2">
                                        <h4 class="font-bold text-gray-800">{{ $project->job->title }}</h4>
                                        @if($project->status == "completed")
                                            <span class="bg-green-100 text-green-800 rounded-full px-2">{{$project->status}}</span>
                                        @elseif($project->status == "active")
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ $project->status }}</span>
                                        @elseif($project->status == 'canceled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ $project->status }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        @if ($project->status == 'completed')
                                            completed {{ \Carbon\Carbon::parse($project->end_date)->diffForHumans() }}
                                        @else
                                            in progress
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div>
                                <p>no projects yet</p>
                            </div>
                        @endforelse
                        {{-- <div class="border rounded-lg overflow-hidden">
                            <img src="https://placehold.co/600x400/E2E8F0/333333?text=Project+2" alt="Portfolio Item" class="w-full h-48 object-cover">
                            <div class="p-4">
                                <h4 class="font-bold text-gray-800">CRM System</h4>
                                <p class="text-sm text-gray-600">Custom CRM for a real estate agency to manage clients and properties.</p>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <!-- Reviews -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Reviews ({{ $freelancer->reviews->count() }})</h3>
                    <div class="space-y-6">
                        <!-- Review 1 -->
                        @forelse ($freelancer->reviews as $review)
                            @php
                                $countRating = $review->rating;
                            @endphp
                            <div class="flex flex-col space-x-2">
                                {{-- <img class="rounded-full mr-4" style="width:3rem; height:3rem;" src="{{ $review->job->user->profile ?  asset('storage/' . $review->job->user->profile->avatar) : 'https://placehold.co/100x100/4B5563/FFFFFF?text='.$review->job->user->profile->name }}" alt="{{ $review->job->user->profile->name }}"> --}}
                                @include('components.user-avatar', ['user' => $review->job->user, 'height' => '2.5rem', 'width' => '2.5rem'])
                                <div>
                                    <div class="flex items-center mb-1">
                                        <h4 class="font-bold text-gray-800 mr-2">{{ $review->job->user->name }}</h4>
                                        <div class="flex text-yellow-400">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < $review->rating)
                                                    <i class="fas fa-star"></i>
                                                @else
                                                    <i class="far fa-star"></i> 
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600">"{{ $review->comment }}"</p>
                                    <p class="text-xs text-gray-400 mt-2">For: {{ $review->job->title }}</p>
                                </div>
                            </div>
                        @empty
                            
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                <!-- Stats -->
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Overall Rating</h3>
                        <div class="text-4xl font-bold accent-purple-text mb-2">{{ number_format($freelancer->reviews->avg('rating'), 1) }} <span class="text-2xl text-gray-400">/ 5</span></div>
                        <div class="flex justify-center text-yellow-400 text-lg">
                            @include('components.review-stars', ['averageRating' => $freelancer->reviews->avg('rating')])
                            @php
                                // $averageRating = $freelancer->reviews->avg('rating'); // Replace with your actual average rating variable
                                // $fullStars = floor($averageRating);
                                // $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                // $totalDisplayStars = $fullStars + ($hasHalfStar ? 1 : 0);

                                //calculate job completion
                                $jobsCompleted = $freelancer->contractsAsFreelancer->where('status', 'completed')->count();
                                $totalJobs = $freelancer->contractsAsFreelancer->count();
                                $percentage = $totalJobs > 0 ? ($jobsCompleted / $totalJobs) * 100 : 0;
                            @endphp
                            {{-- @for ($i = 0; $i < $fullStars; $i++)
                               <i class="fas fa-star"></i>
                            @endfor

                            @if ($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif

                            @for ($i = 0; $i < (5 - $totalDisplayStars); $i++)
                                <i class="far fa-star"></i>
                            @endfor --}}
                             {{-- <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i> --}}
                        </div>
                        <p class="text-sm text-gray-500 mt-2">(Based on {{ $freelancer->reviews->count() }} reviews)</p>
                        <div class="mt-6 grid grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="font-bold text-lg text-gray-800">{{ $jobsCompleted }}</p>
                            <p class="text-gray-500">Jobs Completed</p>
                        </div>
                            <div>
                            <p class="font-bold text-lg text-gray-800">{{ $totalJobs }}</p>
                            <p class="text-gray-500">Total Jobs</p>
                        </div>
                            <div>
                            <p class="font-bold text-lg text-gray-800">{{ number_format($percentage, 2) }}%</p>
                            <p class="text-gray-500">Completion rate</p>
                        </div>
                        </div>
                </div>

                <!-- Skills -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Skills</h3>
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
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Qualifications</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <i class="fas fa-graduation-cap text-gray-400 mt-1 mr-4"></i>
                            <div>
                                <p class="font-semibold text-gray-800">B.Sc. in Computer Science</p>
                                <p class="text-sm text-gray-600">University of Cape Town</p>
                                <p class="text-xs text-gray-400">2012 - 2015</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
@endsection