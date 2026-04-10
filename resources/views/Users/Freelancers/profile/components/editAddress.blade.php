<form method="POST" action="{{ route('freelancer.profile.updateAddress') }}">
    @csrf
    @method('patch')

    <!-- Modal Background -->
    <div id="addressModal"
         class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">

        <!-- Modal Box -->
        <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">
            <h2 class="text-xl font-bold mb-4">Edit Address</h2>

            <!-- Street -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Street</label>
                <input type="text" name="street" id="streetInput"
                       value="{{ $user->profile->address ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- City -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">City</label>
                <input type="text" name="city" id="cityInput"
                       value="{{ $user->profile->city ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- State -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">State</label>
                <input type="text" name="state" id="stateInput"
                       value="{{ $user->profile->state ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Zip Code -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Zip Code</label>
                <input type="text" name="zip_code" id="zipInput"
                       value="{{ $user->profile->zip_code ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Country -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Country</label>
                <input type="text" name="country" id="countryInput"
                       value="{{ $user->profile->country ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-2">
                <button type="button" id="addressCancelBtn"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                    Cancel
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Save
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById("editAddressBtn");
        const modal = document.getElementById("addressModal");
        const cancelBtn = document.getElementById("addressCancelBtn");

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal
        cancelBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });
    });
</script>
