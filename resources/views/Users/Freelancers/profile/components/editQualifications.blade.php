<div id="editQualificationsModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">

        <h2 class="text-xl font-bold mb-4">Edit Qualifications</h2>
        <form method="POST" action="{{ route('freelancer.profile.updateQualifications') }}">
            @csrf
            @method('PATCH')
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Qualifications</label>
                <input type="text" name="degree" id="degreeInput"
                       value="{{ $user->qualification->degree ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">School/Institution</label>
                <input type="text" name="institution" id="institutionInput"
                       value="{{ $user->qualification->institution ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="mb-4">
                <label for="start_year" class="block text-sm font-medium text-gray-700">Year Completed</label>
                <input type="text" name="year_of_completion" id="year_of_completionInput"
                       value="{{ $user->qualification->year_of_completion ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="mb-4 flex justify-end space-x-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                <button type="button" id="cancelQualificationsBtn"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById("editQualificationsBtn");
        const modal = document.getElementById("editQualificationsModal");
        const cancelQualificationsBtn = document.getElementById("cancelQualificationsBtn");

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal when pressing Cancel
        cancelQualificationsBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });
    });
</script>