@extends('dashboards.client.dashboard')

@section('body')
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <img 
                        src="{{ $profile? asset('storage/'.$profile->avatar) : 'https://ui-avatars.com/api/?name=' . $freelancer->name . '&background=random&size=128' }}" 
                        alt="Profile" 
                        class="w-10 h-10 rounded-full border-2 border-blue-500"
                    />
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ $freelancer->name }}</h1>
                        <p class="text-sm text-gray-500">
                            @if ($profile)
                                {{$profile->location}}    
                            @else
                                no address
                            @endif • Member since {{ $freelancer->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-1">
                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="text-sm font-medium text-gray-900">{{ $freelancer->rating }}</span>
                        <span class="text-xs text-gray-500 ml-1">({{ $freelancer->total_reviews }} reviews)</span>
                    </div>
                    <button
                        onclick="toggleFollow()"
                        {{-- class="{{ $isFollowing ? 'bg-red-500 text-white hover:bg-red-600' : 'bg-blue-500 text-white hover:bg-blue-600' }} px-4 py-2 rounded-lg font-medium transition-colors" --}}
                    >
                        {{-- {{ $isFollowing ? 'Following' : 'Follow' }} --}}
                    </button>
                    <button
                        onclick="showMessageModal()"
                        class="px-4 py-2 bg-green-500 text-white rounded-lg font-medium hover:bg-green-600 transition-colors"
                    >
                        Message
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- isFollowing declaration -->
    @php
        $isFollowing = false;
    @endphp

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border p-6 sticky top-24">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">About</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $profile->about ?? 'I\'m a full-stack developer with over 10 years of experience creating high-performance web applications. Specialized in JavaScript frameworks and modern UI/UX design.' }}
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Hourly Rate</h3>
                            <p class="text-2xl font-bold text-gray-900">{{ $freelancer->hourly_rate }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Total Earnings</h3>
                            <p class="text-lg font-semibold text-gray-900">{{ $freelancer->total_earnings }}</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Skills</h3>
                            <div class="flex flex-wrap gap-2">
                                {{-- @foreach($profile->skills as $skill)
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full font-medium">
                                        {{ $skill }}
                                    </span>
                                @endforeach --}}
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Project Stats</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Projects Completed</span>
                                    <span class="font-medium text-gray-900">{{ $freelancer->projects_completed }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Response Time</span>
                                    <span class="font-medium text-gray-900">{{ $freelancer->response_time }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Availability</span>
                                    <span class="font-medium text-gray-900">{{ $freelancer->availability }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="lg:col-span-3">
                <!-- Tabs -->
                <div class="bg-white rounded-xl shadow-sm border mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-8 px-6">
                            @php
                                $tabs = [
                                    ['id' => 'overview', 'label' => 'Overview', 'icon' => 'home'],
                                    ['id' => 'portfolio', 'label' => 'Portfolio', 'icon' => 'folder'],
                                    ['id' => 'reviews', 'label' => 'Reviews', 'icon' => 'star'],
                                    ['id' => 'projects', 'label' => 'Projects', 'icon' => 'briefcase']
                                ];
                                $activeTab = request()->get('tab', 'projects');
                                // dd($activeTab);
                            @endphp
                            @foreach ($tabs as $tab)
                                <button
                                    onclick="setActiveTab('{{ $tab['id'] }}')"
                                    class="nav-link-profile py-4 px-1 border-b-2 font-medium text-sm transition-colors {{ $activeTab === $tab['id'] ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}"
                                >
                                    <span class="flex items-center space-x-2">
                                        <span>{{ $tab['label'] }}</span>
                                    </span>
                                </button>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Tab Content -->
                    <div class="p-6">
                        @if($activeTab === 'overview')
                            <div class="space-y-8">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                                    <p class="text-gray-600 leading-relaxed">
                                        With over a decade of experience in web development, I specialize in creating scalable, 
                                        high-performance applications using modern JavaScript frameworks. My expertise spans 
                                        front-end development, back-end architecture, and full-stack solutions.
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-6 rounded-xl border">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Expertise Areas</h3>
                                        <ul class="space-y-2">
                                            @foreach(['Front-end Development', 'Back-end Architecture', 'Full-stack Solutions', 'API Design'] as $item)
                                                <li class="flex items-center text-gray-700">
                                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                    </svg>
                                                    {{ $item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                    <div class="bg-gradient-to-br from-green-50 to-teal-50 p-6 rounded-xl border">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Recent Projects</h3>
                                        <ul class="space-y-2">
                                            @foreach($freelancer->contractsAsFreelancer as $project)
                                                <li class="flex items-center text-gray-700">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                                                    </svg>
                                                    {{ $project->job->title }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @elseif($activeTab === 'portfolio')
                            <div class="space-y-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Portfolio</h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- $portfolioItems --}}
                                    @if (false)
                                        {{-- Assuming $portfolioItems is an array of portfolio items --}}
                                        @foreach($portfolioItems as $item)
                                            <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-lg transition-shadow duration-300">
                                                <img 
                                                    src="{{ $item['image'] }}" 
                                                    alt="{{ $item['title'] }}"
                                                    class="w-full h-48 object-cover"
                                                />
                                                <div class="p-4">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $item['title'] }}</h3>
                                                    <p class="text-sm text-gray-500">{{ $item['category'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div>
                                            <p class="text-gray-500 text-center">No portfolio items available.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                        @elseif($activeTab === 'reviews')
                            <div class="space-y-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Reviews</h2>
                                <div class="space-y-6">
                                    @foreach($freelancer->reviews as $review)
                                        <div class="bg-white rounded-xl shadow-sm border p-6">
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex items-center space-x-3">
                                                    <img 
                                                        src="https://placehold.co/40x40/6366f1/ffffff?text={{ substr($review->contract->jobs->user->name, 0, 1) }}" 
                                                        alt="{{ $review->contract->jobs->user->name }}"
                                                        class="w-10 h-10 rounded-full"
                                                    />
                                                    <div>
                                                        <h4 class="font-semibold text-gray-900">{{ $review->contract->jobs->user->name }}</h4>
                                                        <div class="flex items-center space-x-1 mt-1">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg 
                                                                    class="w-4 h-4 {{ $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                                    fill="currentColor" 
                                                                    viewBox="0 0 20 20"
                                                                >
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                                </svg>
                                                            @endfor
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at }}</span>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif($activeTab === 'projects')
                            <div class="space-y-6">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Projects</h2>
                                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($freelancer->contractsAsFreelancer as $project)
                                                @php
                                                    $start = Carbon\Carbon::parse($project->job->start_date);
                                                    $end = Carbon\Carbon::parse($project->job->deadline);

                                                    $diff = $end->diff($start);;
                                                @endphp
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $project->job->title }}</td>
                                                    <td class="px-6 py-4 text-sm text-gray-700">{{ $project->job->user->name }}</td>
                                                    <td class="px-6 py-4">
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                            {{ ($project->status === 'completed' ? 'bg-green-100 text-green-800' :
                                                               $project->status === 'active') ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                            {{ $project->status }}
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-700">
                                                        @if ($project->job->deadline > now())
                                                            {{ $diff->format('%m months %d days') }}
                                                        @else
                                                            {{$project->job->deadline}}
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $project->agreed_amount }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 text-white text-center">
                    <h2 class="text-2xl font-bold mb-4">Ready to Work Together?</h2>
                    <p class="text-blue-100 mb-6 max-w-2xl mx-auto">
                        Let's discuss your project and see how I can help bring your vision to life. 
                        I'm available for new opportunities and excited to collaborate on innovative projects.
                    </p>
                    <button
                        onclick="showMessageModal()"
                        class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg"
                    >
                        Send a Message
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full max-h-96 overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Send Message</h3>
                    <button
                        onclick="hideMessageModal()"
                        class="text-gray-400 hover:text-gray-600 text-2xl font-bold"
                    >
                        ×
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input
                            type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="What's this about?"
                        />
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Hello, I'd like to discuss..."
                        ></textarea>
                    </div>
                    
                    <div class="flex space-x-4 pt-4">
                        <button
                            onclick="hideMessageModal()"
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Send Message
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        secondary: '#8b5cf6',
                        accent: '#06b6d4'
                    }
                }
            }
        }
    </script>

{{--  // let activeTab = '{{ $activeTab }}';
        // let isFollowing = {{ $isFollowing ? 'true' : 'false' }}; --}}
    <script>
        let activeTab = '{{ $activeTab }}';
        let isFollowing = {{ $isFollowing ? 'true' : 'false' }};
        
        function setActiveTab(tabId) {
            activeTab = tabId;
            // Update URL hash if needed
            window.location.hash = tabId;
            // Update the active tab visually
            document.querySelectorAll('.nav-link-profile').forEach(link => {
                link.classList.remove('border-blue-500', 'text-blue-600');
                link.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelector(`[onclick="setActiveTab('${tabId}')"]`).classList.remove('border-transparent', 'text-gray-500');
            document.querySelector(`[onclick="setActiveTab('${tabId}')"]`).classList.add('border-blue-500', 'text-blue-600');

            {{$activeTab}} = tabId;

            
            // Update tab content
            const tabContent = document.querySelector('#tab-content');
            if (tabContent) {
                tabContent.innerHTML = '';
                // You would load content dynamically here
            }
        }
        
        function toggleFollow() {
            isFollowing = !isFollowing;
            const followButton = document.querySelector('[onclick="toggleFollow()"]');
            if (isFollowing) {
                followButton.classList.remove('bg-blue-500', 'text-white');
                followButton.classList.add('bg-red-500', 'text-white');
                followButton.textContent = 'Following';
            } else {
                followButton.classList.remove('bg-red-500', 'text-white');
                followButton.classList.add('bg-blue-500', 'text-white');
                followButton.textContent = 'Follow';
            }
        }
        
        function showMessageModal() {
            document.getElementById('messageModal').classList.remove('hidden');
        }
        
        function hideMessageModal() {
            document.getElementById('messageModal').classList.add('hidden');
        }
        
        // Initialize active tab from URL hash
        window.addEventListener('load', function() {
            const hash = window.location.hash.substring(1);
            if (hash && document.querySelector(`[onclick="setActiveTab('${hash}')"]`)) {
                setActiveTab(hash);
            }
        });
    </script>
@endsection