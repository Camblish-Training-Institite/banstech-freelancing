        <div class="lg:grid lg:grid-cols-3 lg:gap-8">

            <!-- Left Column: Contest Details -->
            <div class="lg:col-span-2">
                <div class="bg-white  shadow-sm rounded-lg p-6">

                    <div class="prose prose-lg max-w-none text-gray-600 ">
                        <h2 class="text-2xl font-bold mb-4">Contest Description</h2>
                        {!! $contest->description !!}
                    </div>
                </div>

                {{-- Attatched Files --}}
                @if($contest->file_url)
                <div class="mt-6 bg-white  shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Attached Files</h3>
                    <a href="{{ asset('storage/' . $contest->file_url) }}" target="_blank"
                        class="text-indigo-600 hover:underline">
                        {{ basename($contest->file_url) }}
                    </a>
                </div>
                @endif


                <!-- Required Skills -->
                <div class="mt-8">

                    @if($contest->required_skills)
                    @php
                    // Attempt to decode the skills field as JSON
                    $contestSkills = $contest->required_skills ? explode(',', $contest->required_skills) : [];
                    // dd($jobSkills);
                    $contestSkills = str_replace('\"', '"', $contestSkills); // Replace escaped quotes

                    // If json_decode fails (e.g., invalid JSON), fallback to manual parsing
                    // if (!$jobSkills || !is_array($jobSkills)) {
                    // // Remove surrounding brackets and backslashes, then explode by commas
                    // $jobSkills = trim($job->skills, '[]'); // Remove square brackets
                    // $jobSkills = str_replace('\"', '"', $jobSkills); // Replace escaped quotes
                    // $jobSkills = explode(',', $jobSkills); // Split by comma
                    // $jobSkills = array_map('trim', $jobSkills); // Trim whitespace
                    // }
                    // dd($jobSkills);

                    @endphp
                    <div class="mt-6 bg-white  shadow-sm rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Required Skills</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($contestSkills as $skill)
                            <span
                                class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">
                                {{ $skill }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                


            </div>


            <!-- Right Column: Summary & Actions -->
            <div class="mt-8 lg:mt-0">

                @include('Users.Freelancers.components.about-client')

                <div class="bg-white shadow-sm rounded-lg p-6 sticky top-6">
                    <h3
                        class="text-xl font-bold text-gray-900 border-b border-gray-200  pb-4 mb-4">
                        Contest Summary</h3>

                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 ">Prize</dt>
                            <dd class="mt-1 text-2xl font-bold text-indigo-600 ">${{
                                number_format($contest->prize_money, 2) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 ">Status</dt>
                            <dd class="mt-1">
                                @php
                                $statusClass = '';
                                if ($contest->status === 'open') {
                                $statusClass = 'bg-green-100 text-green-800 ';
                                } elseif ($contest->status === 'closed') {
                                $statusClass = 'bg-red-100 text-red-800';
                                } else {
                                $statusClass = 'bg-blue-100 text-blue-800';
                                }
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium {{ $statusClass }}">
                                    {{ ucfirst($contest->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Closes On</dt>
                            <dd class="mt-1 text-md font-semibold text-gray-900">{{
                                \Carbon\Carbon::parse($contest->closing_date)->format('d F, Y') }}</dd>
                        </div>
                    </div>

                    @if ($contest->status === 'open' && $contest->closing_date >= now()->toDateString())
                    <div class="mt-6">
                        <a href="{{ route('freelancer.contests.submit.show', $contest) }}"
                            class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-600 hover:bg-green-700 md:py-4 md:text-lg md:px-10">
                            Submit Entry
                        </a>
                    </div>
                    @else
                    <div
                        class="mt-6 p-4 bg-yellow-100  text-yellow-800  rounded-lg text-center">
                        This contest is now closed.
                    </div>
                    @endif
                </div>
            </div>
        </div>