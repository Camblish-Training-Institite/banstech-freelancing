<!-- Profile Edit Modal -->
<div id="profileModal" class="hidden inset-0 bg-black bg-opacity-50 items-center justify-center z-50 mx-3 w-[50%] fixed">
    <div class="bg-white rounded-lg shadow-lg w-96 p-6" style="width:60rem;"> 

        <h2 class="text-xl font-bold mb-4">Edit Profile</h2>

        <form id="profileForm" method="POST" action="{{ route('freelancer.profile.updateProfile') }}">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium">First Name</label>
                <input type="text" name="first_name" class="mt-1 block w-full rounded-md border-gray-300"
                       value="{{ $user->profile->first_name ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Last Name</label>
                <input type="text" name="last_name" class="mt-1 block w-full rounded-md border-gray-300"
                       value="{{ $user->profile->last_name ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" class="mt-1 block w-full rounded-md border-gray-300"
                       value="{{ $user->profile->title ?? '' }}">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Location</label>
                <input type="text" name="location" class="mt-1 block w-full rounded-md border-gray-300"
                       value="{{ $user->profile->location ?? '' }}">
            </div>

            <div class="flex justify-end space-x-2">
                <button type="button" id="cancelBtn" class="px-4 py-2 bg-gray-200 rounded-md">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
    const editProfileBtn = document.getElementById('editProfileBtn');
    const profileModal = document.getElementById('profileModal');
    const cancelBtn = document.getElementById('cancelBtn');

    if (editProfileBtn) {
        editProfileBtn.addEventListener('click', () => {
            profileModal.classList.remove('hidden');
            profileModal.classList.add('flex');
        });
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            profileModal.classList.add('hidden');
            profileModal.classList.remove('flex');
        });
    }
</script>
