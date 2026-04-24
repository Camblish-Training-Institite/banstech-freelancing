
<!-- Opportunity Cards -->
@forelse ($jobs as $job)
    @php
        $jobSkills = $job->skills ? explode(',', $job->skills) : [];
        $jobSkills = str_replace('\"', '"', $jobSkills);
    @endphp
    <div class="opportunity-card">
        <div class="content-block">
            <div class="opportunity-summary">
                <h2 class="m-0">{{ $job->title . ' | ' . $job->job_type }}</h2>
                <div class="opportunity-meta">
                    <span>Client: {{ $job->user->name ?? 'Unknown client' }}</span>
                    @if ($job->category)
                        <span>Category: {{ $job->category->name }}</span>
                    @endif
                    <span>{{ $job->job_funded ? 'Funded' : 'Not funded' }}</span>
                    <span>Ends {{ \Carbon\Carbon::parse($job->deadline)->diffForHumans() }}</span>
                </div>
            </div>
            <p class="opportunity-excerpt">{{ $job->description }}</p>
            <div class="tags">
                @forelse ($jobSkills as $skill)
                    <span>{{ $skill }}</span>
                @empty
                    <span style="padding:0.5rem; background-color:#ddd; color:#000; border-radius:4px;">No skills for this job</span>
                @endforelse
            </div>
        </div>
        <div class="price-and-proposals-block listing-actions">
            <div class="price">R {{ number_format($job->budget, 2) }}</div>
            <div class="proposals">{{ $job->proposals->count() }} Proposals</div>
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
    {{ $jobs->appends(request()->query())->links() }}
</div>
