<div id="editCertificatesModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">
        <h2 class="text-xl font-bold mb-4">Edit Certificates</h2>
        <form method="POST" action="{{ route('freelancer.profile.updateCertificates') }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Certificates</label>
                <input type="text" name="certificates" id="certificatesInput"
                       value="{{ $user->profile->certificates ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class mb-4>
                <label class="block text-sm font-medium text-gray-700">Issuing Organization</label>
                <input type="text" name="issuing_organization" id="issuingOrganizationInput"
                       value="{{ $user->profile->issuing_organization ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class mb-4>
                <label for="issue_date" class="block text-sm font-medium text-gray-700">Issue Date</label>
                <input type="text" name="issue_date" id="issueDateInput"
                       value="{{ $user->profile->issue_date ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">

                <label for="expiry_date" class="block text-sm font-medium text-gray-700 mt-4">Expiry Date</label>
                <input type="text" name="expiry_date" id="expiryDateInput"
                       value="{{ $user->profile->expiry_date ?? '' }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
                <button type="button" id="cancelCertificatesBtn"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button> 
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById("editCertificatesBtn");
        const modal = document.getElementById("editCertificatesModal");
        const cancelCertificatesBtn = document.getElementById("cancelCertificatesBtn");

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal when pressing Cancel
        cancelCertificatesBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });
    });
</script>