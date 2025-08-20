<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Active Jobs') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Active Jobs") }}
                </div>
            </div>
        </div><!-- VIEW OFFER MODAL -->
        <div x-show="showOfferViewModal" x-cloak
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-transition.opacity>
            <div @click.away="showOfferViewModal = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] flex flex-col" x-transition.scale>
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">You've Received an Offer!</h2>
                    <button @click="showOfferViewModal = false"
                        class="text-gray-400 hover:text-gray-700 text-3xl">&times;</button>
                </div>
                <div class="p-8 overflow-y-auto space-y-6">
                    <div>
                        <h3 class="font-semibold text-lg">API Integration for SaaS Platform</h3>
                        <p class="text-gray-500">From: <span class="font-medium text-gray-700">Tech Innovators</span>
                        </p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-sm text-gray-600 font-medium">Message from Client:</p>
                        <p class="mt-2 text-gray-800">"Hi John, we were very impressed with your proposal. We'd like to
                            offer you the project. We've adjusted the budget slightly to $3,200 to account for some
                            additional complexity. We're excited to work with you!"</p>
                    </div>
                    <div class="border-t pt-6">
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-gray-600">Offered Amount</p>
                            <p class="text-3xl font-bold text-green-600">$3,200</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1 text-right">Original Bid: $3,000</p>
                    </div>
                </div>
                <div class="p-6 border-t bg-gray-50 grid grid-cols-3 gap-4 rounded-b-xl">
                    <button @click="showOfferViewModal = false"
                        class="font-semibold text-white bg-green-600 px-6 py-3 rounded-lg hover:bg-green-700 col-span-1">Accept
                        Offer</button>
                    <button @click="showOfferViewModal = false"
                        class="font-semibold text-gray-700 bg-white border px-6 py-3 rounded-lg hover:bg-gray-100 col-span-1">Decline</button>
                    <button @click="showOfferViewModal = false"
                        class="font-semibold text-indigo-600 bg-indigo-100 px-6 py-3 rounded-lg hover:bg-indigo-200 col-span-1">Negotiate
                        Further</button>
                </div>
            </div>
        </div>

</x-app-layout>