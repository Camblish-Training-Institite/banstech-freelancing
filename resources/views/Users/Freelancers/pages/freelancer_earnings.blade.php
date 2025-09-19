<!-- Main Content -->
<main class="flex-1 overflow-y-auto p-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Billing & Payouts</h1>

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
                    <p class="text-sm text-gray-500">Pending Payouts</p>
                    {{-- <p class="text-2xl font-bold text-gray-800">R 4,500.00</p> --}}
                    <p class="text-2xl font-bold text-gray-800">R{{ number_format($PendingPayouts) ?? 0, 2 }} </p>
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
            <!-- Pending Payouts -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Pending Milestone Payouts</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Job / Project</th>
                                <th scope="col" class="px-6 py-3">Milestone</th>
                                <th scope="col" class="px-6 py-3">Due Date</th>
                                <th scope="col" class="px-6 py-3">Amount</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    E-commerce Platform</th>
                                <td class="px-6 py-4">Phase 2: Backend Dev</td>
                                <td class="px-6 py-4">Sep 15, 2025</td>
                                <td class="px-6 py-4 font-semibold">R 2,500.00</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">In
                                        Review</span>
                                </td>
                            </tr>
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">CRM
                                    System</th>
                                <td class="px-6 py-4">Final Milestone</td>
                                <td class="px-6 py-4">Sep 10, 2025</td>
                                <td class="px-6 py-4 font-semibold">R 2,000.00</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">Funded</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Payouts Table -->
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-gray-800">Pending Payouts</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Date Requested</th>
                                <th class="px-6 py-3">Project</th>
                                <th class="px-6 py-3">Milestone</th>
                                <th class="px-6 py-3">Amount</th>
                                <th class="px-6 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($payouts as $payout)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $payout->requested_at }}</td>
                                    <td class="px-6 py-4">{{ $payout->contract->job->title }}</td>
                                    <td class="px-6 py-4">Frontpage</td>
                                    <td class="px-6 py-4 font-semibold">R {{ number_format($payout->amount, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                            {{ $payout->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No pending payouts
                                        found.</td>
                                </tr>
                            @endforelse
                    </table>
                    </tbody>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Withdraw Funds -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Withdraw Funds</h3>
                <div class="mb-4">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount to
                        withdraw</label>
                    <input type="text" id="amount"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5"
                        placeholder="R 0.00" required />
                </div>
                <div class="mb-4">
                    <label for="method" class="block mb-2 text-sm font-medium text-gray-900">Withdrawal Method</label>
                    <select id="method"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5">
                        <option selected>Choose a method</option>
                        <option value="bank">Bank Transfer - FNB (...1234)</option>
                        <option value="paypal">PayPal - bobby@omen.co.za</option>
                    </select>
                </div>
                <button
                    class="w-full accent-purple hover:opacity-90 text-white font-bold py-2.5 px-4 rounded-lg text-sm transition-all duration-300">
                    <i class="fas fa-paper-plane mr-2"></i>Request Withdrawal
                </button>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Recent Transactions</h3>
                <ul class="space-y-4">
                    <li class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-full mr-4">
                            <i class="fas fa-arrow-down text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Milestone Payout</p>
                            <p class="text-sm text-gray-500">Aug 28, 2025</p>
                        </div>
                        <p class="ml-auto font-bold text-green-600">+ R 1,500.00</p>
                    </li>
                    <li class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-full mr-4">
                            <i class="fas fa-arrow-up text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Withdrawal</p>
                            <p class="text-sm text-gray-500">Aug 25, 2025</p>
                        </div>
                        <p class="ml-auto font-bold text-red-600">- R 5,000.00</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>
