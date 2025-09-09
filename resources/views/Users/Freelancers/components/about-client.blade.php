<aside class="space-y-6 mt-8 lg:mt-0 py-3">
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-4">About Client</h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        @php
                            $userProfile = $user->profile ?? null;
                        @endphp
                        <span>{{ $userProfile ? $userProfile->location : "N/A"  }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="flex items-center">
                            @for ($i = 0; $i < 5; $i++) <svg class="w-5 h-5 text-yellow-400" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                @endfor
                                <span class="ml-2 font-semibold">5.0</span>
                        </div>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0h18M12 15.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>Member since: {{ $user->created_at->format('d M, Y') }}</span>
                    </li>
                </ul>
                <hr class="my-4 border-gray-200">
                <ul class="space-y-3">
                    @php
                    $verifications = [
                    'Payment Verified', 'Deposit Mode', 'Email Verified',
                    'Phone Verified', 'Profile Completed'
                    ];
                    @endphp
                    @foreach ($verifications as $item)
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm text-gray-700">{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>