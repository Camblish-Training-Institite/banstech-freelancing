

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
            <div class="flex flex-col w-full justify-between items-start mb-2">
                <!-- user image and name div -->
                <div class="flex items-center space-x-4 py-2 w-full">
                    <img class="rounded-full mr-2" style="height:3rem; width:3rem; object-fit:cover;" src="{{ $freelancer->profile ? asset('storage/'. $freelancer->profile->avatar) : 'https://ui-avatars.com/api/?name='. $freelancer->name .'&background=random&size=128' }}" alt="">
                    <h2 class="m-0">{{ $freelancer->name }}</h2>
                </div>

                <!-- user review and joined date div -->
                <div class="flex flex-col justify-center">
                    <div class="flex w-full space-x-2">
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
                            <p class="text-medium font-semibold" style="color: rgb(134, 134, 134);">no ratings yet</p>
                        @endif
                    </div>
                    <h5 class="text-xs text-gray-600 font-bold">Joined {{  \Carbon\Carbon::parse($freelancer->created_at)->diffForHumans() }}</h5>
                </div>
            </div>
            <!-- user bio and skills -->
            <p>{{$freelancer->profile? $freelancer->profile->bio : 'no bio'}}</p>
            <div class="tags">
                @forelse ($freelancerSkills as $skill)
                    <span>{{$skill}}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No skills added</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block">
            {{-- <div class="price">R {{ number_format($freelancer->budget, 2) }}</div>
            <div class="proposals">{{$freelancer->proposals->count()}} Proposals</div> --}}
            <div class="buttons-block">
                <a href="{{ route('client.freelancer.profile', $freelancer->id) }}" class="view-job">View Profile</a>
                <a href="{{ route('user', $freelancer->id) }}" class="send-proposal">Message</a>
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