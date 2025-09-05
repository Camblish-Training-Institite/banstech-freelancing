<div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <a href="#" @click.prevent="tab = 'details'"
                            :class="{ 'border-indigo-500 text-indigo-600': tab === 'details', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'details' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Details
                        </a>

                        <a href="#" @click.prevent="tab = 'entries'"
                            :class="{ 'border-indigo-500 text-indigo-600': tab === 'entries', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'entries' }"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Entries
                        </a>
                    </nav>
                </div>