<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proposals') }}
        </h2>
    </x-slot>

    <!-- NEW PROPOSALS PAGE -->
                        <div x-show="dashboardPage === 'proposals'" x-cloak>
                            <h2 class="text-3xl font-bold text-gray-800">My Proposals</h2>
                            <p class="mt-1 text-gray-600">Track and manage all the proposals you've sent to clients.</p>
                            
                            <div class="mt-6 bg-white p-6 rounded-xl shadow-sm">
                                <div class="border-b border-gray-200">
                                    <nav class="-mb-px flex space-x-8">
                                        <a href="#" @click.prevent="proposalTab = 'active'" class="py-3 px-1 border-b-2 tab-link" :class="{'active': proposalTab === 'active'}">Active (4)</a>
                                        <a href="#" @click.prevent="proposalTab = 'offers'" class="py-3 px-1 border-b-2 tab-link" :class="{'active': proposalTab === 'offers'}">Offers Received (1)</a>
                                        <a href="#" @click.prevent="proposalTab = 'archived'" class="py-3 px-1 border-b-2 tab-link" :class="{'active': proposalTab === 'archived'}">Archived (8)</a>
                                    </nav>
                                </div>

                                <!-- Active Proposals List -->
                                <div x-show="proposalTab === 'active'" class="mt-6 space-y-4">
                                    <!-- Proposal Item: Viewed -->
                                    <div class="border rounded-lg p-4 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-lg text-indigo-700">Build a Responsive E-commerce Website</h4>
                                            <p class="text-sm text-gray-500">To: <span class="font-medium">MegaCorp Inc.</span> | Submitted: 3 days ago</p>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <p class="font-bold text-gray-800 text-lg">$2,800</p>
                                            <span class="status-tag status-viewed">Viewed by Client</span>
                                            <button class="text-gray-400 hover:text-gray-700"><i class="fas fa-ellipsis-v"></i></button>
                                        </div>
                                    </div>
                                    <!-- Proposal Item: Negotiating -->
                                    <div class="border rounded-lg p-4 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-lg text-indigo-700">Mobile App UI/UX Design</h4>
                                            <p class="text-sm text-gray-500">To: <span class="font-medium">Creative Solutions</span> | Submitted: 5 days ago</p>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <p class="font-bold text-gray-800 text-lg">$4,500</p>
                                            <span class="status-tag status-negotiating">Negotiating</span>
                                            <button class="font-semibold text-sm primary-button text-white px-4 py-2 rounded-md">View Message</button>
                                        </div>
                                    </div>
                                    <!-- More active proposals... -->
                                </div>

                                <!-- Offers Received List -->
                                <div x-show="proposalTab === 'offers'" x-cloak class="mt-6 space-y-4">
                                    <!-- Proposal Item: Offer Received -->
                                    <div class="border-2 border-green-500 bg-green-50 rounded-lg p-4 flex justify-between items-center">
                                        <div>
                                            <h4 class="font-semibold text-lg text-green-800">API Integration for SaaS Platform</h4>
                                            <p class="text-sm text-green-700">From: <span class="font-medium">Tech Innovators</span> | Submitted: 1 week ago</p>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <p class="font-bold text-gray-800 text-lg">$3,200</p>
                                            <span class="status-tag status-offer">Offer Received</span>
                                            <button @click="showOfferViewModal = true" class="font-semibold text-sm primary-button text-white px-4 py-2 rounded-md">View Offer</button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Archived Proposals List -->
                                <div x-show="proposalTab === 'archived'" x-cloak class="mt-6 space-y-4">
                                    <!-- Proposal Item: Declined -->
                                    <div class="border rounded-lg p-4 flex justify-between items-center opacity-70">
                                        <div>
                                            <h4 class="font-semibold text-lg text-gray-600">Data Analytics Dashboard</h4>
                                            <p class="text-sm text-gray-500">To: <span class="font-medium">Data Insights LLC</span> | Submitted: 2 weeks ago</p>
                                        </div>
                                        <div class="flex items-center gap-6">
                                            <p class="font-bold text-gray-500 text-lg">$5,000</p>
                                            <span class="status-tag status-declined">Declined</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    
</x-app-layout>