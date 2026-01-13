@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="lg:grid lg:grid-cols-12 lg:gap-8">
        {{-- Main Content --}}
        <main class="lg:col-span-9">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Application Reports</h1>
                <p class="mt-1 text-sm text-gray-600">An overview of all activity across the platform.</p>
            </div>

            <!-- General Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-purple-100 p-6 rounded-xl shadow-lg text-purple-800">
                    <h2 class="text-purple-600 text-sm font-medium uppercase">Total Revenue</h2>
                    <p class="text-3xl font-bold mt-2">R {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-green-100 p-6 rounded-xl shadow-lg text-green-800">
                    <h2 class="text-green-600 text-sm font-medium uppercase">Total Bookings</h2>
                    <p class="text-3xl font-bold mt-2">{{ $totalBookings }}</p>
                </div>
                <div class="bg-blue-100 p-6 rounded-xl shadow-lg text-blue-800">
                    <h2 class="text-blue-600 text-sm font-medium uppercase">Total Providers</h2>
                    <p class="text-3xl font-bold mt-2">{{ $totalProviders }}</p>
                </div>
                <div class="bg-yellow-100 p-6 rounded-xl shadow-lg text-yellow-800">
                    <h2 class="text-yellow-600 text-sm font-medium uppercase">Total Users</h2>
                    <p class="text-3xl font-bold mt-2">{{ $totalUsers }}</p>
                </div>
            </div>

            <div class="space-y-8">
                 <!-- Monthly Revenue -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-semibold text-gray-800 mb-4">Monthly Revenue (Last 6 Months)</h3>
                    <div class="space-y-4">
                        @forelse($monthlyRevenue as $month)
                        <div class="flex justify-between items-center">
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($month->month . '-01')->format('F Y') }}</p>
                            <p class="font-bold text-gray-800">R {{ number_format($month->revenue, 2) }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">No revenue data available.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Top Providers -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-semibold text-gray-800 mb-4">Top 5 Providers by Bookings</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($topProviders as $provider)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-700">{{ $provider->provider_name }}</span>
                            <span class="text-sm font-bold text-indigo-600">{{ $provider->bookings_count }} Bookings</span>
                        </li>
                        @empty
                        <li class="py-3 text-sm text-gray-500">No provider data available.</li>
                        @endforelse
                    </ul>
                </div>
                 <!-- Most Booked Rooms -->
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-semibold text-gray-800 mb-4">Top 5 Most Booked Rooms</h3>
                    <ul class="divide-y divide-gray-200">
                        @forelse($mostBookedRooms as $room)
                        <li class="py-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">{{ $room->provider_name }} - Room {{ $room->room_number }}</span>
                                <span class="text-sm font-bold text-indigo-600">{{ $room->bookings_count }} Bookings</span>
                            </div>
                            <p class="text-xs text-gray-500">{{ $room->room_type }}</p>
                        </li>
                        @empty
                        <li class="py-3 text-sm text-gray-500">No room data available.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </main>

        {{-- Aside/Sidebar --}}
        <aside class="lg:col-span-3 mt-8 lg:mt-0">
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-semibold text-gray-800 mb-4">Download Reports</h3>
                    {{-- {{ route('admin.reports.download.pdf') }} --}}
                    <a href="#" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Download Full Report (PDF)
                    </a>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg">
                    <h3 class="font-semibold text-gray-800 mb-4">Booking Statuses</h3>
                    <div class="space-y-3">
                        @foreach($bookingStatusDistribution as $status => $count)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-600 capitalize">{{ str_replace('_', ' ', $status) }}</span>
                            <span class="font-medium text-gray-800">{{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection

