

<!-- Opportunity Cards -->
@forelse ($projectManagers as $projectManager)
    @php
        // Attempt to decode the skills field as JSON
        $projectManagerSkills = $projectManager->skills ? explode(',', $projectManager->skills) : [];
        // dd($projectManagerSkills);
        $projectManagerSkills = str_replace('\"', '"', $projectManagerSkills); 

        // $deadline = \Carbon\Carbon::parse($projectManager->deadline);
        
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <!-- user name and joined date div -->
            <div class="flex flex-col w-full justify-between items-start mb-2">
                <!-- user image and name div -->
                <div class="flex items-center space-x-4 py-2 w-full">
                    <img class="rounded-full mr-2" style="height:3rem; width:3rem; object-fit:cover;" src="{{ $projectManager->profile ? asset('storage/'. $projectManager->profile->avatar) : 'https://ui-avatars.com/api/?name='. $projectManager->name .'&background=random&size=128' }}" alt="">
                    <h2 class="m-0">{{ $projectManager->name }}</h2>
                </div>

                <!-- user review and joined date div -->
                <div class="flex flex-col justify-center">
                    <div class="flex w-full space-x-2">
                        @if ($projectManager->reviews->count() > 0)
                            @php
                                $averageRating = $projectManager->reviews()->avg('rating');
                                $roundedRating = round($averageRating);
                            @endphp

                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $roundedRating)
                                    <i class="fas fa-star text-yellow-500"></i>
                                @else
                                    <i class="far fa-star text-yellow-500"></i>
                                @endif
                            @endfor
                            <span class="text-sm text-gray-600">({{ number_format($averageRating, 1) }} out of 5)</span>
                        @else
                            <p class="text-medium font-semibold" style="color: rgb(134, 134, 134);">no ratings yet</p>
                        @endif
                    </div>
                    <h5 class="text-xs text-gray-600 font-bold">Joined {{  \Carbon\Carbon::parse($projectManager->created_at)->diffForHumans() }}</h5>
                </div>
            </div>
            <!-- user bio and skills -->
            <p>{{$projectManager->profile? $projectManager->profile->bio : 'no bio'}}</p>
            <div class="tags">
                @forelse ($projectManagerSkills as $skill)
                    <span>{{$skill}}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No skills added</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block">
            {{-- <div class="price">R {{ number_format($projectManager->budget, 2) }}</div>
            <div class="proposals">{{$projectManager->proposals->count()}} Proposals</div> --}}
            <div class="buttons-block">
                <a href="{{ route('client.freelancer.profile', $projectManager->id) }}" class="view-job">Request to Manage Project</a>
                <a href="{{ route('user', $projectManager->id) }}" class="send-proposal">Message</a>
            </div>
        </div>
    </div>
@empty
    <p>No jobs available at the moment. Please check back later.</p>
@endforelse

<!-- Pagination -->
<div class="pagination">
    {{ $projectManagers->links() }} <!-- Pagination links -->
</div>