@extends('Users.Clients.layouts.body.dashboard-body')

@section('active-tab')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profile Management</h2>
        <p class="mt-2 text-sm text-gray-600">Update the details clients will use across your dashboard experience.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 md:p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PATCH')

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Profile Photo</h3>
                    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
                        @if ($profile->avatar)
                            <img src="{{ asset('storage/' . $profile->avatar) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover border border-gray-200">
                        @else
                            @include('components.user-avatar', ['user' => $user, 'width' => '5rem', 'height' => '5rem'])
                        @endif
                        <input type="file" name="avatar" id="avatar" class="block w-full text-sm text-gray-500 file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $profile->first_name) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $profile->last_name) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                    </div>
                    <div class="md:col-span-2">
                        <label for="bio" class="block text-sm font-medium text-gray-700">Bio</label>
                        <textarea name="bio" id="bio" rows="4" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $profile->bio) }}</textarea>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Business Details</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                            <input type="text" name="company" id="company" value="{{ old('company', $profile->company) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" name="location" id="location" value="{{ old('location', $profile->location) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone</label>
                            <select name="timezone" id="timezone" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Timezone</option>
                                @foreach (timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ old('timezone', $profile->timezone) == $tz ? 'selected' : '' }}>
                                        {{ $tz }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Address</h3>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $profile->address) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">City / Town</label>
                            <input type="text" name="city" id="city" value="{{ old('city', $profile->city) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State / Region</label>
                            <input type="text" name="state" id="state" value="{{ old('state', $profile->state) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip / Postal Code</label>
                            <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code', $profile->zip_code) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                            <input type="text" name="country" id="country" value="{{ old('country', $profile->country) }}" class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Save Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
