@php
    $payoutHeaders = ['Date Requested', 'Project', 'Milestone', 'Amount', 'Status'];
    $payoutRows = [
        fn ($payout) => optional($payout->requested_at)->format('d M Y H:i') ?? 'N/A',
        fn ($payout) => e(optional($payout->contract?->job)->title ?? 'N/A'),
        fn ($payout) => e(optional($payout->milestone)->title ?? 'Final contract release'),
        fn ($payout) => 'R ' . number_format($payout->amount, 2),
        fn ($payout) => '<span class="text-xs font-medium px-2.5 py-0.5 rounded-full ' . ($payout->status === 'processed' ? 'bg-green-100 text-green-800' : ($payout->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800')) . '">' . e($payout->status) . '</span>',
    ];

    $payoutMobile = [
        'titleIndex' => 1,
        'subtitleIndex' => 2,
        'primaryIndex' => 3,
        'statusIndex' => 4,
        'excludeIndices' => [],
    ];

    $withdrawalHeaders = ['Date Requested', 'Method', 'Destination', 'Amount', 'Status'];
    $withdrawalRows = [
        fn ($withdrawalRequest) => optional($withdrawalRequest->requested_at)->format('d M Y H:i') ?? 'N/A',
        fn ($withdrawalRequest) => $withdrawalRequest->method === 'paypal' ? 'PayPal payout' : 'Bank transfer (manual)',
        fn ($withdrawalRequest) => $withdrawalRequest->method === 'paypal'
            ? e(data_get($withdrawalRequest->destination_details, 'email', 'N/A'))
            : e(data_get($withdrawalRequest->destination_details, 'bank_name', 'N/A')) . ' (...' . (substr((string) data_get($withdrawalRequest->destination_details, 'account_number', ''), -4) ?: 'N/A') . ')',
        fn ($withdrawalRequest) => 'R ' . number_format($withdrawalRequest->amount, 2),
        fn ($withdrawalRequest) => '<span class="text-xs font-medium px-2.5 py-0.5 rounded-full ' . ($withdrawalRequest->status === 'processed' ? 'bg-green-100 text-green-800' : ($withdrawalRequest->status === 'failed' ? 'bg-red-100 text-red-800' : ($withdrawalRequest->status === 'confirmed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800'))) . '">' . e($withdrawalRequest->status) . '</span>',
    ];

    $withdrawalMobile = [
        'titleIndex' => 1,
        'subtitleIndex' => 2,
        'primaryIndex' => 3,
        'statusIndex' => 4,
        'excludeIndices' => [],
    ];
@endphp

<main class="flex-1 overflow-y-auto p-4 md:p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Billing & Payouts</h1>

    @if (session('status'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Financial Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-dollar-sign fa-lg text-green-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Total Earnings</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">R 25,650.00</p> --}}
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($TotalEarnings) ?? 0, 2 }} </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-hourglass-half fa-lg text-yellow-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Pending Withdrawals</p>
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($PendingWithdrawals ?? 0, 2) }} </p>
                </div>
            </div>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-wallet fa-lg text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500">Available to Withdraw</p>
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($AvailableWithdrawals) ?? 0, 2 }} </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-8">
           
            <!-- Incoming Payouts Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Incoming Payouts</h3>
                </div>
                <div class="p-4 md:p-6">
                    <x-dashboard-table
                        :headers="$payoutHeaders"
                        :items="$payouts"
                        :rows="$payoutRows"
                        :mobile-config="$payoutMobile"
                        :show-id="false"
                    >
                        <x-slot:empty>No payouts found.</x-slot:empty>
                    </x-dashboard-table>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Withdrawal Requests</h3>
                </div>
                <div class="p-4 md:p-6">
                    <x-dashboard-table
                        :headers="$withdrawalHeaders"
                        :items="$withdrawalRequests"
                        :rows="$withdrawalRows"
                        :mobile-config="$withdrawalMobile"
                        :show-id="false"
                    >
                        <x-slot:empty>No withdrawal requests yet.</x-slot:empty>
                    </x-dashboard-table>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Withdraw Funds -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Withdraw Funds</h3>
                <form method="POST" action="{{ route('freelancer.withdrawals.store') }}" class="space-y-4">
                    @csrf
                    <div class="mb-4">
                        <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount to
                            withdraw</label>
                        <input type="number" step="0.01" min="0.01" max="{{ number_format($AvailableWithdrawals, 2, '.', '') }}"
                            id="amount" name="amount" value="{{ old('amount') }}"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                         focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                            placeholder="0.00" required />
                    </div>
                    <div class="mb-4">
                        <label for="method" class="block mb-2 text-sm font-medium text-gray-900">Withdrawal Method</label>
                        <select id="method"
                            name="method"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                         focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5">
                            <option value="">Choose a method</option>
                            <option value="bank" {{ old('method') === 'bank' ? 'selected' : '' }}>
                                {{ Auth::user()->bankDetail ? 'Bank Transfer - ' . Auth::user()->bankDetail->bank_name . ' (' . Auth::user()->bankDetail->masked_account_number . ')' : 'Bank Transfer - add bank details in profile' }}
                            </option>
                            <option value="paypal" {{ old('method') === 'paypal' ? 'selected' : '' }}>PayPal - {{ Auth::user()->email }}</option>
                        </select>
                    </div>
                    <p class="text-sm text-gray-500">
                        Available now: R{{ number_format($AvailableWithdrawals, 2) }}.
                        Pending and confirmed withdrawal requests are deducted from this balance immediately.
                    </p>
                    <p class="text-sm text-gray-500">
                        PayPal withdrawals are sent through PayPal. Bank withdrawals are approved in the platform and then paid manually by admin.
                    </p>
                    <button type="submit"
                    class="w-full accent-purple hover:opacity-90 text-black font-bold py-2.5 px-4 
                    rounded-lg text-sm transition-all duration-300">
                        <i class="fas fa-paper-plane mr-2"></i>Request Withdrawal
                    </button>
                </form>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Transactions</h3>
                <ul class="space-y-4">
                    @forelse ($payouts->take(5) as $payout)
                        <li class="flex items-center">
                            <div class="p-2 {{ $payout->status === 'failed' ? 'bg-red-100' : 'bg-green-100' }} rounded-full mr-4">
                                <i class="fas {{ $payout->status === 'failed' ? 'fa-times text-red-600' : 'fa-arrow-down text-green-600' }}"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ optional($payout->milestone)->title ?? 'Contract payout' }}</p>
                                <p class="text-sm text-gray-500">{{ optional($payout->requested_at)->format('d M Y H:i') ?? 'N/A' }}</p>
                            </div>
                            <p class="ml-auto font-bold {{ $payout->status === 'failed' ? 'text-red-600' : 'text-green-600' }}">
                                {{ $payout->status === 'failed' ? '-' : '+' }} R {{ number_format($payout->amount, 2) }}
                            </p>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">No payout activity yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</main>
