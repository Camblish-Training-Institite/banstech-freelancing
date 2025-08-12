<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Active Jobs') }}
        </h2>
    </x-slot>

    <!-- Offers Received List -->
    <div x-show="proposalTab === 'offers'" x-cloak class="mt-6 space-y-4">
        <!-- Proposal Item: Offer Received -->
        <div class="border-2 border-green-500 bg-green-50 rounded-lg p-4 flex justify-between items-center">
            <div>
                <h4 class="font-semibold text-lg text-green-800">API Integration for SaaS Platform</h4>
                <p class="text-sm text-green-700">From: <span class="font-medium">Tech Innovators</span> | Submitted: 1
                    week ago</p>
            </div>
            <div class="flex items-center gap-6">
                <p class="font-bold text-gray-800 text-lg">$3,200</p>
                <span class="status-tag status-offer">Offer Received</span>
                <button @click="showOfferViewModal = true"
                    class="font-semibold text-sm primary-button text-white px-4 py-2 rounded-md">View Offer</button>
            </div>
        </div>
    </div>

</x-app-layout>