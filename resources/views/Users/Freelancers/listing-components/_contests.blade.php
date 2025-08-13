

<!-- Opportunity Cards -->
@foreach ($contests as $contest)
    @php
        // Attempt to decode the requried_skills field as JSON
        $contestSkills = $contest->requried_skills ? explode(',', $contest->requried_skills) : [];
        // dd($contestrequried_Skills);
        $contestSkills = str_replace('\"', '"', $contestSkills); // Replace escaped quotes

        // If json_decode fails (e.g., invalid JSON), fallback to manual parsing
        // if (!$contestrequried_Skills || !is_array($contestrequried_Skills)) {
        //     // Remove surrounding brackets and backslashes, then explode by commas
        //     $contestrequried_Skills = trim($contest->requried_skills, '[]'); // Remove square brackets
        //     $contestrequried_Skills = str_replace('\"', '"', $contestrequried_Skills); // Replace escaped quotes
        //     $contestrequried_Skills = explode(',', $contestrequried_Skills); // Split by comma
        //     $contestrequried_Skills = array_map('trim', $contestrequried_Skills); // Trim whitespace
        // }
        // dd($contestrequried_Skills);
        
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <h2>{{ $contest->title }}</h2>
            <p>{{$contest->description}}</p>
            <div class="tags">
                @forelse ($contestSkills as $skill)
                    <span>{{$skill}}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No requried_skills for this contest</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block">
            <div class="price">R {{ number_format($contest->prize_money, 2) }}</div>
            <div class="proposals">{{$contest->entries->count()}} Enries</div>
            <div class="buttons-block">
                <a href="{{ route('freelancer.contests.show', $contest->id) }}" class="view-contest">View contest</a>
                <button class="send-proposal">Send Proposal</button>
            </div>
        </div>
    </div>
@endforeach

<!-- Pagination -->
<div class="pagination">
    {{ $contests->links() }} <!-- Pagination links -->
</div>