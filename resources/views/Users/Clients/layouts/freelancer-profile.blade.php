@extends('dashboards.client.dashboard')

@section('body')
    @php
        $displayName = $profile?->full_name ?: $freelancer->name;
        $displayTitle = $profile?->title ?: 'Freelancer';
        $displayLocationParts = array_filter([
            $profile?->location,
            $profile?->city,
            $profile?->country,
        ]);
        $displayLocation = count($displayLocationParts) ? implode(', ', $displayLocationParts) : 'Location not provided';
        $portfolioItems = $freelancer->portfolio;
        $completedProjects = $freelancer->contractsAsFreelancer->where('status', 'completed');
        $reviews = $freelancer->reviews;
        $averageRating = (float) ($reviews->avg('rating') ?? 0);
        $jobsCompleted = $completedProjects->count();
        $totalJobs = $freelancer->contractsAsFreelancer->count();
        $completionRate = $totalJobs > 0 ? ($jobsCompleted / $totalJobs) * 100 : 0;
    @endphp

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

        .freelancer-profile-hero {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .freelancer-profile-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.6rem 1rem;
            margin-top: 0.35rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .freelancer-profile-page {
                padding: 1rem;
            }

            .freelancer-profile-hero {
                flex-direction: column;
                align-items: flex-start;
            }

            .freelancer-profile-hero img {
                height: 5rem;
                width: 5rem;
            }

            .freelancer-profile-hero-copy {
                width: 100%;
            }

            .freelancer-profile-meta {
                flex-direction: column;
                gap: 0.35rem;
                align-items: flex-start;
            }

            .freelancer-profile-actions {
                width: 100%;
                margin-left: 0;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                margin-top: 0.5rem;
            }

            .freelancer-profile-actions form,
            .freelancer-profile-actions button {
                width: 100%;
            }

            .freelancer-profile-actions button {
                display: inline-flex;
                justify-content: center;
            }
        }
    </style>

    <div class="flex w-full justify-start">
        <a href="{{ url()->previous() }}" class="back-link">← Back</a>
    </div>

    <main class="freelancer-profile-page flex-1 overflow-y-auto p-8">
        <div class="freelancer-profile-hero bg-white w-full rounded-lg shadow-md p-6 mb-8 relative">
            <img
                class="h-32 w-32 rounded-full object-cover border-4 border-white"
                src="{{ $profile && $profile->avatar ? asset('storage/' . $profile->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($freelancer->name) . '&background=random&size=128' }}"
                alt="{{ $displayName }}"
            >
            <div class="freelancer-profile-hero-copy md:ml-6">
                <h2 class="text-2xl font-bold text-gray-800">{{ $displayName }}</h2>
                <p class="text-gray-600">{{ $displayTitle }}</p>
                <div class="freelancer-profile-meta">
                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $displayLocation }}
                    • Member since {{ $freelancer->created_at->diffForHumans() }}
                </div>
            </div>
            <div class="freelancer-profile-actions ml-auto flex space-x-4">
                <form method="POST" action="{{ route('client.inbox.start', $freelancer) }}">
                    @csrf
                    <button type="submit" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-6 rounded-lg text-sm transition-all duration-300">
                        <i class="fas fa-envelope mr-2"></i>Message
                    </button>
                </form>
                <form method="POST" action="{{ route('client.inbox.start', $freelancer) }}">
                    @csrf
                    <input type="hidden" name="intent" value="invite">
                    <button type="submit" class="accent-purple hover:opacity-90 text-white font-bold py-2 px-6 rounded-lg text-sm transition-all duration-300">
                        Send Job Invite
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">About Me</h3>
                    <p class="text-gray-600">
                        {{ $profile?->bio ?: 'This freelancer has not added a bio yet.' }}
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Portfolio</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse ($portfolioItems as $item)
                            <div class="border rounded-lg overflow-hidden">
                                <img
                                    src="https://placehold.co/600x400/E2E8F0/333333?text={{ urlencode($item->title) }}"
                                    alt="{{ $item->title }}"
                                    class="w-full h-48 object-cover"
                                >
                                <div class="p-4">
                                    <div class="flex w-full space-x-2 items-center justify-between mb-2">
                                        <h4 class="font-bold text-gray-800">{{ $item->title }}</h4>
                                        @if ($item->file_url)
                                            <a href="{{ $item->file_url }}" target="_blank" rel="noopener noreferrer" class="text-sm accent-purple-text font-semibold">Open</a>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        {{ $item->description ?: 'No description provided.' }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            @forelse ($completedProjects as $project)
                                <div class="border rounded-lg overflow-hidden">
                                    <img src="https://placehold.co/600x400/E2E8F0/333333?text={{ urlencode($project->job->title ?? 'Completed Project') }}" alt="{{ $project->job->title ?? 'Completed Project' }}" class="w-full h-48 object-cover">
                                    <div class="p-4">
                                        <div class="flex w-full space-x-2 items-center justify-between mb-2">
                                            <h4 class="font-bold text-gray-800">{{ $project->job->title ?? 'Completed Project' }}</h4>
                                            <span class="bg-green-100 text-green-800 rounded-full px-2 text-xs font-semibold">completed</span>
                                        </div>
                                        <p class="text-sm text-gray-600">
                                            Completed {{ $project->end_date ? \Carbon\Carbon::parse($project->end_date)->diffForHumans() : 'recently' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div>
                                    <p class="text-gray-600">No portfolio items or completed projects yet.</p>
                                </div>
                            @endforelse
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Reviews ({{ $reviews->count() }})</h3>
                    <div class="space-y-6">
                        @forelse ($reviews as $review)
                            <div class="flex flex-col space-y-2">
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
                            <p class="text-gray-600">No reviews yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Overall Rating</h3>
                    <div class="text-4xl font-bold accent-purple-text mb-2">
                        {{ number_format($averageRating, 1) }} <span class="text-2xl text-gray-400">/ 5</span>
                    </div>
                    <div class="flex justify-center text-yellow-400 text-lg">
                        @include('components.review-stars', ['averageRating' => $averageRating])
                    </div>
                    <p class="text-sm text-gray-500 mt-2">(Based on {{ $reviews->count() }} reviews)</p>
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
                            <p class="font-bold text-lg text-gray-800">{{ number_format($completionRate, 2) }}%</p>
                            <p class="text-gray-500">Completion Rate</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Skills</h3>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($profile?->skills ?? collect() as $skill)
                            <span class="bg-gray-200 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">{{ $skill->name }}</span>
                        @empty
                            <p class="text-gray-600">No skills added yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Qualifications</h3>
                    @if ($freelancer->qualification)
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-graduation-cap text-gray-400 mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $freelancer->qualification->degree }}</p>
                                    <p class="text-sm text-gray-600">{{ $freelancer->qualification->institution }}</p>
                                    <p class="text-xs text-gray-400">Completed {{ $freelancer->qualification->year_of_completion }}</p>
                                </div>
                            </li>
                        </ul>
                    @else
                        <p class="text-gray-600">No qualifications added yet.</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Certificates</h3>
                    @if ($freelancer->certificate)
                        <ul class="space-y-4">
                            <li class="flex items-start">
                                <i class="fas fa-certificate text-gray-400 mt-1 mr-4"></i>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $freelancer->certificate->certificate_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $freelancer->certificate->issuing_organization ?: 'Issuing organization not provided' }}</p>
                                    @if ($freelancer->certificate->issue_date)
                                        <p class="text-xs text-gray-400">Issued {{ $freelancer->certificate->issue_date->format('Y-m-d') }}</p>
                                    @endif
                                    @if ($freelancer->certificate->expiration_date)
                                        <p class="text-xs text-gray-400">Expires {{ $freelancer->certificate->expiration_date->format('Y-m-d') }}</p>
                                    @endif
                                    @if ($freelancer->certificate->credential_url)
                                        <a href="{{ $freelancer->certificate->credential_url }}" target="_blank" rel="noopener noreferrer" class="text-sm accent-purple-text font-semibold">View credential</a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    @else
                        <p class="text-gray-600">No certificates added yet.</p>
                    @endif
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Profile Details</h3>
                    <div class="space-y-3 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-900">Email:</span> {{ $freelancer->email }}</p>
                        <p><span class="font-semibold text-gray-900">Company:</span> {{ $profile?->company ?: 'Not provided' }}</p>
                        <p><span class="font-semibold text-gray-900">Address:</span> {{ $profile?->address ?: 'Not provided' }}</p>
                        <p><span class="font-semibold text-gray-900">City:</span> {{ $profile?->city ?: 'Not provided' }}</p>
                        <p><span class="font-semibold text-gray-900">State:</span> {{ $profile?->state ?: 'Not provided' }}</p>
                        <p><span class="font-semibold text-gray-900">Country:</span> {{ $profile?->country ?: 'Not provided' }}</p>
                        <p><span class="font-semibold text-gray-900">Timezone:</span> {{ $profile?->timezone ?: 'Not provided' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
