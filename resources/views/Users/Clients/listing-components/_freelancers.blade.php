

<!-- Opportunity Cards -->
@forelse ($freelancers as $freelancer)
    @php
        // Attempt to decode the skills field as JSON
        $freelancerSkills = $freelancer->skills ? explode(',', $freelancer->skills) : [];
        // dd($freelancerSkills);
        $freelancerSkills = str_replace('\"', '"', $freelancerSkills); 

        // $deadline = \Carbon\Carbon::parse($freelancer->deadline);
        
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <!-- user name and joined date div -->
            <div class="opportunity-summary">
                <!-- user image and name div -->
                <div class="listing-person py-2">
                    <img class="rounded-full mr-2" style="height:3rem; width:3rem; object-fit:cover;" src="{{ $freelancer->profile ? asset('storage/'. $freelancer->profile->avatar) : 'https://ui-avatars.com/api/?name='. $freelancer->name .'&background=random&size=128' }}" alt="">
                    <div class="listing-person-details">
                        <h2 class="m-0">{{ $freelancer->name }}</h2>
                        <p class="listing-side-note">Joined {{  \Carbon\Carbon::parse($freelancer->created_at)->diffForHumans() }}</p>
                    </div>
                </div>

                <!-- user review and joined date div -->
                <div class="opportunity-meta">
                    <div class="flex w-full flex-wrap items-center gap-2">
                        @if ($freelancer->reviews->count() > 0)
                            @php
                                $averageRating = $freelancer->reviews->avg('rating'); // Replace with your actual average rating variable
                                $fullStars = floor($averageRating);
                                $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                                $totalDisplayStars = $fullStars + ($hasHalfStar ? 1 : 0);

                                $averageRating = $freelancer->reviews()->avg('rating');
                                $roundedRating = round($averageRating);
                            @endphp

                            
                            @for ($i = 0; $i < $fullStars; $i++)
                               <i class="fas fa-star"></i>
                            @endfor

                            @if ($hasHalfStar)
                                <i class="fas fa-star-half-alt"></i>
                            @endif

                            @for ($i = 0; $i < (5 - $totalDisplayStars); $i++)
                                <i class="far fa-star"></i>
                            @endfor
                            <span class="text-sm text-gray-600">({{ number_format($averageRating, 1) }} out of 5)</span>
                        @else
                            <p class="text-medium font-semibold" style="color: rgb(134, 134, 134);">No ratings yet</p>
                        @endif
                    </div>
                </div>
            </div>
            <!-- user bio and skills -->
            <p class="opportunity-excerpt">{{ $freelancer->profile? $freelancer->profile->bio : 'No bio' }}</p>
            <div class="tags">
                @forelse ($freelancerSkills as $skill)
                    <span>{{$skill}}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No skills added</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block listing-actions">
            {{-- <div class="price">R {{ number_format($freelancer->budget, 2) }}</div>
            <div class="proposals">{{$freelancer->proposals->count()}} Proposals</div> --}}
            <div class="buttons-block">
                <a href="{{ route('client.freelancer.profile', $freelancer->id) }}" class="view-job">View Profile</a>
                <form method="POST" action="{{ route('client.inbox.start', $freelancer) }}">
                    @csrf
                    <button type="submit" class="send-proposal">Message</button>
                </form>
            </div>
        </div>
    </div>
@empty
    <p>No jobs available at the moment. Please check back later.</p>
@endforelse

<!-- Pagination -->
<div class="pagination">
    {{ $freelancers->links() }} <!-- Pagination links -->
</div>
