

<!-- Opportunity Cards -->
@foreach ($jobs as $job)
    @php
        // Attempt to decode the skills field as JSON
        $jobSkills = $job->skills ? explode(',', $job->skills) : [];
        // dd($jobSkills);
        $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes

        // If json_decode fails (e.g., invalid JSON), fallback to manual parsing
        // if (!$jobSkills || !is_array($jobSkills)) {
        //     // Remove surrounding brackets and backslashes, then explode by commas
        //     $jobSkills = trim($job->skills, '[]'); // Remove square brackets
        //     $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes
        //     $jobSkills = explode(',', $jobSkills); // Split by comma
        //     $jobSkills = array_map('trim', $jobSkills); // Trim whitespace
        // }
        // dd($jobSkills);
        
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <h2>{{ $job->title }}</h2>
            <p>{{$job->description}}</p>
            <div class="tags">
                @forelse ($jobSkills as $skill)
                    <span>{{$skill}}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No skills for this job</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block">
            <div class="price">R {{ number_format($job->budget, 2) }}</div>
            <div class="proposals">{{$job->proposals->count()}} Proposals</div>
            <div class="buttons-block">
                <a href="{{ route('freelancer.jobs.show', $job->id) }}" class="view-job">View Job</a>
                <a href="{{ route('freelancer.proposal.create', $job->id) }}" class="send-proposal">Send Proposal</a>
            </div>
        </div>
    </div>
@endforeach

<!-- Pagination -->
<div class="pagination">
    {{ $jobs->links() }} <!-- Pagination links -->
</div>