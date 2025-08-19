<style>
    /* Tags */
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 1rem 0;
    }
    .tags span {
        background-color: #f0eaff;
        color: #516aae;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<div class="bg-white rounded-lg shadow-md p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Project Details</h3>
    <div>
        <p class="text-gray-600">
            {{ $project->job->description }}
        </p>
        <!-- Tags -->
        @if(!empty($project->job->skills))
            <div class="tags">
                @php
                    $jobSkills = $project->job->skills ? explode(',', $project->job->skills) : [];
                    // dd($jobSkills);
                    $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes
                @endphp
                @foreach($jobSkills as $skill)
                    <span>{{ trim($skill) }}</span>
                @endforeach
            </div>
        @endif
    </div>
    
</div>