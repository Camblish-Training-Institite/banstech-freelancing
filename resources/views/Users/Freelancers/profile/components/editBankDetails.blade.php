<form method="POST" action="{{ route('freelancer.profile.updateBankDetails') }}">
    @csrf
    @method('patch')

    <div id="bankDetailsModal"
         class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">
            <h2 class="text-xl font-bold mb-4">Edit Bank Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Holder Name</label>
                    <input type="text" name="account_holder_name"
                           value="{{ old('account_holder_name', $user->bankDetail->account_holder_name ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Bank Name</label>
                    <input type="text" name="bank_name"
                           value="{{ old('bank_name', $user->bankDetail->bank_name ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Number</label>
                    <input type="text" name="account_number"
                           value="{{ old('account_number', $user->bankDetail->account_number ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Account Type</label>
                    <select name="account_type"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Select type</option>
                        @foreach (['checking', 'savings', 'current', 'business'] as $accountType)
                            <option value="{{ $accountType }}" {{ old('account_type', $user->bankDetail->account_type ?? '') === $accountType ? 'selected' : '' }}>
                                {{ ucfirst($accountType) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Branch Code</label>
                    <input type="text" name="branch_code"
                           value="{{ old('branch_code', $user->bankDetail->branch_code ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SWIFT Code</label>
                    <input type="text" name="swift_code"
                           value="{{ old('swift_code', $user->bankDetail->swift_code ?? '') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>

            <p class="mt-4 text-sm text-gray-500">
                Leave every field empty and save if you want to remove your saved bank details.
            </p>

            <div class="flex justify-end space-x-2 mt-6">
                <button type="button" id="bankDetailsCancelBtn"
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
        const openBtn = document.getElementById("editBankDetailsBtn");
        const modal = document.getElementById("bankDetailsModal");
        const cancelBtn = document.getElementById("bankDetailsCancelBtn");

        if (openBtn) {
            openBtn.addEventListener("click", () => {
                modal.classList.remove("hidden");
                modal.classList.add("flex");
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
                modal.classList.remove("flex");
            });
        }
    });
</script>
