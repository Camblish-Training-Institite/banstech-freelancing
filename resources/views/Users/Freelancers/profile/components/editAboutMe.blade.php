<form method="POST" action="{{ route('freelancer.profile.updateAboutMe') }}">
    @csrf
    @method('PATCH')

    <div id="aboutMeModal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3">
        <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;">
            <h2 class="text-xl font-bold mb-4">Edit About Me</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">About Me</label>
                <textarea name="bio" id="aboutMeInput" rows="4"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">{{$user->profile->bio ?? ''}}</textarea>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelAbtMeBtn"
                    class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save</button>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const openBtn = document.getElementById("editAboutMeBtn");
        const modal = document.getElementById("aboutMeModal");
        const cancelAbtMeBtn = document.getElementById("cancelAbtMeBtn");

        // Open modal
        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        });

        // Close modal when pressing Cancel
        cancelAbtMeBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        });

        
    });
</script>