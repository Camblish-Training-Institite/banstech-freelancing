<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Find Next Opportunity') }}
        </h2>
    </x-slot>

    <div x-show="dashboardPage === 'findWork'" x-cloak>
                           <h1 class="text-4xl font-extrabold text-gray-900">Find Your Next Opportunity</h1>
                           <p class="text-lg text-gray-600 mt-2">Search through thousands of jobs and find the one that's right for you.</p>
                           <div class="flex gap-8 mt-8">
                                <aside class="w-1/4 flex-shrink-0"><div class="bg-white p-6 rounded-xl shadow-sm">
                                    <h3 class="font-bold text-lg mb-4 border-b pb-3">Filters</h3>
                                    <div><label class="font-semibold text-sm">Keyword</label><input type="text" placeholder="Job title, skills..." class="mt-1 w-full border rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"></div>
                                    <div class="mt-4"><label class="font-semibold text-sm">Category</label><select class="mt-1 w-full border rounded-md px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"><option>All Categories</option></select></div>
                                    <div class="mt-4"><label class="font-semibold text-sm">Job Type</label><div class="space-y-2 mt-2"><label class="flex items-center text-sm"><input type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 mr-2">Fixed-Price</label></div></div>
                                    <button class="w-full mt-6 bg-indigo-600 text-white py-2.5 rounded-lg font-semibold hover:bg-indigo-700">Apply Filters</button>
                                </div></aside>
                                <main class="w-3/4">
                                    <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500">
                                        <h3 class="text-xl font-bold text-indigo-600">Build a Responsive E-commerce Website with React</h3>
                                        <p class="text-gray-700 mt-4">We are looking for an experienced React developer...</p>
                                        <div class="flex justify-between items-end mt-4 pt-4 border-t">
                                            <p class="font-bold text-lg text-green-600">$1,500 - $3,000</p>
                                            <a href="#" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700">View & Apply</a>
                                        </div>
                                    </div>
                                </main>
                           </div>
                        </div>

   
</x-app-layout>