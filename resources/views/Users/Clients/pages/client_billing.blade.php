<!-- Main Content -->
<main class="flex-1 overflow-y-auto p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Billing & Payments</h1>

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-arrow-circle-up fa-lg text-red-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Paid Out</p>
                    <p class="text-2xl font-bold text-gray-800">R {{ number_format($totalSpent ?? 0,2) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-full">
                    <i class="fas fa-lock fa-lg accent-purple-text"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Funds in Escrow</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">R 1000</p> --}}
                    <p class="text-2xl font-bold text-gray-800">R {{ number_format($fundsInEscrow ?? 0,2)}}</p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-wallet fa-lg text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Available Balance</p>
                    <p class="text-2xl font-bold text-gray-800">R {{ number_format($availableBalance ?? 0,2)}}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Active Jobs & Milestones -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Active Jobs & Milestones</h3>
                </div>
                <div class="p-6 space-y-6">
                   @forelse ($jobs as $job)
                    <div>
                        <h4 class="font-bold text-gray-800">{{$job->title}}</h4>
                        <p class="text-sm text-gray-500">Freelancer: {{ $job->contract?->user?->name ?? 'Not assigned yet' }}</p>
                        <p class="text-sm text-gray-500">Contract Amount: R {{ number_format($job->contract?->agreed_amount ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500 mb-4">Escrowed: R {{ number_format($job->contract?->remainingEscrowAmount() ?? 0, 2) }}</p>
                        <ul class="space-y-3">
                            @forelse ($job->contract->milestones as $index => $milestone)
                               <li class="p-4 border rounded-lg flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">Phase {{$index+1}}: {{$milestone->title}} </p>
                                    <p class="text-sm text-gray-500">R {{ number_format($milestone->amount, 2) }}</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-xs font-medium mr-4 px-2.5 py-0.5 rounded-full {{ $milestone->status === 'released' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        <i class="fas {{ $milestone->status === 'released' ? 'fa-check-circle' : 'fa-hourglass-half' }} mr-1"></i>{{ ucfirst($milestone->status) }}
                                    </span>
                                </div>
                            </li> 
                            @empty
                                <li class="text-sm text-gray-500">No milestones created yet.</li>
                            @endforelse
                        </ul>

                        @if ($job->contract)
                            @php
                                $remainingToFund = max(($job->contract->agreed_amount ?? 0) - $job->contract->remainingEscrowAmount(), 0);
                            @endphp

                            <form method="POST" action="{{ route('billing.jobs.fund', $job) }}" class="mt-4 flex flex-col sm:flex-row gap-3">
                                @csrf
                                <input
                                    type="number"
                                    name="amount"
                                    step="0.01"
                                    min="0.01"
                                    value="{{ number_format($remainingToFund, 2, '.', '') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full sm:w-56 p-2.5"
                                />
                                <button
                                    type="submit"
                                    class="accent-purple hover:opacity-90 text-black font-bold py-2.5 px-4 rounded-lg text-sm transition-all duration-300 {{ $remainingToFund <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    @disabled($remainingToFund <= 0)
                                >
                                    Move To Escrow
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="border-t border-gray-200"></div>   
                   @empty
                       <p class="text-sm text-gray-500">No active contract funding records yet.</p>
                   @endforelse
                    
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Add Funds -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Add Funds to Balance</h3>
                <form method="POST" action="{{ route('billing.deposits.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount to add</label>
                        <input type="number" step="0.01" min="1" name="amount" id="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="0.00" required />
                    </div>
                    <div class="mb-4">
                        <label for="method" class="block mb-2 text-sm font-medium text-gray-900">Payment Method</label>
                        <p class="text-sm text-gray-600 p-2.5 bg-gray-50 rounded-lg border">PayPal sandbox checkout</p>
                        <p class="mt-2 text-xs text-gray-500">
                            Region currency: {{ $currencyContext['local_currency'] }}.
                            PayPal checkout currency: {{ $currencyContext['paypal_currency'] }}.
                            @if($currencyContext['requires_conversion'])
                                Your wallet amount will be converted before redirecting to PayPal because PayPal does not support {{ $currencyContext['local_currency'] }}.
                            @endif
                        </p>
                        <p class="mt-2 text-xs text-gray-500">You’ll be redirected to PayPal to approve the top-up, and your wallet will only be credited after PayPal capture succeeds.</p>
                    </div>
                    <button class="w-full accent-purple hover:opacity-90 text-black font-bold py-2.5 px-4 rounded-lg text-sm transition-all duration-300">
                        <i class="fas fa-paypal mr-2"></i>Continue to PayPal
                    </button>
                </form>
            </div>

            <!-- Transaction History -->
                <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Transaction History</h3>
                <ul class="space-y-4">
                    @forelse ($fundings->take(6) as $funding)
                        @php
                            $isWalletDeposit = is_null($funding->job_id);
                            $title = $isWalletDeposit
                                ? ($funding->status === 'deposited'
                                    ? 'Funds Added'
                                    : ($funding->status === 'pending' ? 'Awaiting PayPal Approval' : 'Failed Payment'))
                                : 'Funds In Escrow';
                            $iconClass = $isWalletDeposit
                                ? ($funding->status === 'deposited'
                                    ? 'fa-arrow-down text-green-600'
                                    : ($funding->status === 'pending' ? 'fa-clock text-purple-600' : 'fa-times-circle text-red-600'))
                                : 'fa-lock text-purple-600';
                            $wrapperClass = $isWalletDeposit
                                ? ($funding->status === 'deposited'
                                    ? 'bg-green-100'
                                    : ($funding->status === 'pending' ? 'bg-purple-100' : 'bg-red-100'))
                                : 'bg-purple-100';
                            $amountClass = $isWalletDeposit
                                ? ($funding->status === 'deposited'
                                    ? 'text-green-600'
                                    : ($funding->status === 'pending' ? 'text-purple-600' : 'text-red-600'))
                                : 'text-purple-600';
                        @endphp
                        <li class="flex items-center">
                            <div class="p-2 {{ $wrapperClass }} rounded-full mr-4">
                                <i class="fas {{ $iconClass }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $title }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ $funding->job?->title ?? 'Wallet balance top-up' }}
                                    @if (is_null($funding->job_id) && $funding->paypal_currency)
                                        • PayPal {{ $funding->paypal_currency }} {{ number_format($funding->paypal_amount ?? 0, 2) }}
                                    @endif
                                </p>
                            </div>
                            <p class="ml-auto font-bold {{ $amountClass }}">
                                {{ $funding->status === 'deposited' && $isWalletDeposit ? '+' : '' }} R {{ number_format($funding->amount, 2) }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">No funding activity yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</main>
