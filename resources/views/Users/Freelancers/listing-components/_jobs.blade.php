

<!-- Opportunity Cards -->
@forelse ($jobs as $job)
    @php
        // Attempt to decode the skills field as JSON
        $jobSkills = $job->skills ? explode(',', $job->skills) : [];
        // dd($jobSkills);
        $jobSkills = str_replace('\"', '"', $jobSkills); 

        // $deadline = \Carbon\Carbon::parse($job->deadline);
        
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <div class="flex flex-col w-full justify-between items-start mb-2">
                <h2 class="m-0">{{ $job->title }}</h2>
                <h5 class="text-xs text-gray-600 font-bold">End Date: {{  \Carbon\Carbon::parse($job->deadline)->diffForHumans() }}</h5>
            </div>
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
@empty
    <p>No jobs available at the moment. Please check back later.</p>
@endforelse

<!-- Pagination -->
<div class="pagination">
    {{ $jobs->links() }} <!-- Pagination links -->
</div>