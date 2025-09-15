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
                    <p class="text-sm text-gray-500">Total Spent</p>
                    <p class="text-2xl font-bold text-gray-800">R 15,200.00</p>
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
                    <p class="text-2xl font-bold text-gray-800">R 4,500.00</p>
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
                    <p class="text-2xl font-bold text-gray-800">R 8,000.00</p>
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
                    <!-- Job 1 -->
                    <div>
                        <h4 class="font-bold text-gray-800">E-commerce Platform</h4>
                        <p class="text-sm text-gray-500 mb-4">Freelancer: Bobby Drake</p>
                        <ul class="space-y-3">
                            <li class="p-4 border rounded-lg flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">Phase 1: UI/UX Design</p>
                                    <p class="text-sm text-gray-500">R 1,500.00</p>
                                </div>
                                <div class="flex items-center">
                                    <span class="bg-green-100 text-green-800 text-xs font-medium mr-4 px-2.5 py-0.5 rounded-full"><i class="fas fa-check-circle mr-1"></i>Released</span>
                                </div>
                            </li>
                            <li class="p-4 border rounded-lg flex items-center justify-between bg-blue-50 border-blue-200">
                                <div>
                                    <p class="font-semibold">Phase 2: Backend Dev</p>
                                    <p class="text-sm text-gray-500">R 2,500.00</p>
                                </div>
                                <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    <i class="fas fa-lock-open mr-2"></i>Release Funds
                                </button>
                            </li>
                            <li class="p-4 border rounded-lg flex items-center justify-between">
                                <div>
                                    <p class="font-semibold">Phase 3: Deployment</p>
                                    <p class="text-sm text-gray-500">R 1,000.00</p>
                                </div>
                                <button class="accent-purple hover:opacity-90 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Fund Milestone
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="border-t border-gray-200"></div>
                    <!-- Job 2 -->
                        <div>
                        <h4 class="font-bold text-gray-800">CRM System</h4>
                        <p class="text-sm text-gray-500 mb-4">Freelancer: Bobby Drake</p>
                        <ul class="space-y-3">
                                <li class="p-4 border rounded-lg flex items-center justify-between bg-blue-50 border-blue-200">
                                <div>
                                    <p class="font-semibold">Final Milestone</p>
                                    <p class="text-sm text-gray-500">R 2,000.00</p>
                                </div>
                                <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg text-sm">
                                    <i class="fas fa-lock-open mr-2"></i>Release Funds
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-8">
            <!-- Add Funds -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Add Funds to Balance</h3>
                <div class="mb-4">
                    <label for="amount" class="block mb-2 text-sm font-medium text-gray-900">Amount to add</label>
                    <input type="text" id="amount" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block w-full p-2.5" placeholder="R 0.00" required />
                </div>
                <div class="mb-4">
                    <label for="method" class="block mb-2 text-sm font-medium text-gray-900">Payment Method</label>
                    <p class="text-sm text-gray-600 p-2.5 bg-gray-50 rounded-lg border">Visa ending in 4242</p>
                </div>
                <button class="w-full accent-purple hover:opacity-90 text-white font-bold py-2.5 px-4 rounded-lg text-sm transition-all duration-300">
                    <i class="fas fa-plus-circle mr-2"></i>Add Funds
                </button>
            </div>

            <!-- Transaction History -->
                <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Transaction History</h3>
                <ul class="space-y-4">
                    <li class="flex items-center">
                        <div class="p-2 bg-red-100 rounded-full mr-4">
                            <i class="fas fa-arrow-up text-red-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Milestone Funded</p>
                            <p class="text-sm text-gray-500">CRM System - Final</p>
                        </div>
                        <p class="ml-auto font-bold text-red-600">- R 2,000.00</p>
                    </li>
                        <li class="flex items-center">
                        <div class="p-2 bg-green-100 rounded-full mr-4">
                            <i class="fas fa-arrow-down text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Funds Added</p>
                            <p class="text-sm text-gray-500">From Visa 4242</p>
                        </div>
                        <p class="ml-auto font-bold text-green-600">+ R 10,000.00</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>