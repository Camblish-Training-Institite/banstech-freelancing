<!-- Entries Section -->
                <div class="mt-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Entries</h3>
                    <div class="bg-white shadow-sm rounded-lg">
                        {{-- You can loop through and display contest entries here --}}
                        {{-- Example of an entry card --}}
                        <div class="p-6 border-b border-gray-200">
                            @if($contest->entries->count() > 0)
                            @foreach($contest->entries as $entry)
                            <div class="mb-4">
                                <h4 class="text-lg font-semibold text-gray-900">{{
                                    $entry->freelancer->name }}</h4>
                                <p class="text-gray-600">{{ $entry->description }}</p>
                                <p class="text-sm text-gray-500">Submitted on: {{
                                    $entry->created_at->format('M d, Y') }}</p>
                            </div>
                            @endforeach
                            @else
                            <p class="text-gray-600">No entries submitted yet.</p>
                            @endif
                        </div>
                    </div>
                </div>