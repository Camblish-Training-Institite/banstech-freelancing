@extends('layouts.admin')

@section('content')

    <style>
        /* Custom colors from your provided file */
        :root {
            --color-primary-p: #ad68e4;
            --color-complimentary: #68e4ad;
            --color-dark-bg: #282c34;
        }
        .bg-primary-p { background-color: var(--color-primary-p); }
        .text-primary-p { color: var(--color-primary-p); }
        .hover\:bg-primary-p-darker:hover { background-color: #973fdf; }
        .bg-complimentary { background-color: var(--color-complimentary); }
        .text-complimentary { color: var(--color-complimentary); }
        .bg-dark-bg { background-color: var(--color-dark-bg); }

        body {
            font-family: 'Inter', sans-serif;
            color: #333333;
        }
    </style>

    <main class="flex-grow container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold mb-8 text-center text-dark-bg">Welcome to the Oplanla.com Admin Panel!</h1>

        <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-lg">
            {{-- The $partnerName variable is passed from the DashboardController --}}
            <h2 class="text-3xl font-bold mb-6 text-dark-bg">Hello, {{ Auth::guard('admin')->user()->username}}!</h2>
            <p class="text-gray-700 mb-8">Here's a quick overview of the property's performance and quick links to manage our listings.</p>

            {{-- These stats should be passed as variables from your controller --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm text-center">
                    <p class="text-5xl font-bold text-primary-p mb-2">{{ $upcomingBookings->num_bookings }}</p>
                    <p class="text-gray-600">Upcoming Bookings</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm text-center">
                    @if ($upcomingBookings->payouts)
                        <p class="text-5xl font-bold text-complimentary mb-2">R {{ $upcomingBookings->payouts }}</p>
                    @else
                        <p class="text-3xl font-bold text-complimentary mb-2">No upcoming payouts</p>
                    @endif
                    <p class="text-gray-600">Pending Payouts</p>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm text-center">
                    @if ($totalProfits->profits)
                        <p class="text-5xl font-bold text-blue-500 mb-2">R {{ $totalProfits->profits }}</p>
                    @else
                        <p class="text-3xl font-bold text-blue-500 mb-2">No money made yet</p>
                    @endif
                    <p class="text-gray-600">Total profits made</p>
                </div>
                {{-- <div class="bg-gray-50 p-6 rounded-lg shadow-sm text-center">
                    
                        @if ($avgReview->avg_rating)
                            <p class="text-5xl font-bold text-blue-500 mb-2">
                                {{ round($avgReview->avg_rating, 2) }}
                            </p>
                        @else
                            <p class="text-3xl font-bold text-blue-500 mb-2">
                                Not yet reviewed
                            </p>
                        @endif
                    <p class="text-gray-600">Average Rating</p>
                </div> --}}
                <div class="bg-gray-50 p-6 rounded-lg shadow-sm text-center">
                    <p class="text-5xl font-bold text-purple-500 mb-2">{{ $activeRooms->num_rooms }}</p>
                    <p class="text-gray-600">Active Listings</p>
                </div>
            </div>

            <h3 class="text-2xl font-bold mb-4 text-dark-bg">Quick Links</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                {{-- Tip: Use the route() helper for cleaner links --}}
                <a href="{{route('rooms.index')}}" class="bg-primary-p text-white font-semibold py-3 px-6 rounded-lg hover:bg-primary-p-darker transition duration-300 text-center">Manage Listings</a>
                <a href="{{route('room.booking.index')}}" class="bg-complimentary text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-600 transition duration-300 text-center">View All Bookings</a>
                <a href="#" class="bg-blue-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-blue-600 transition duration-300 text-center">Payment History</a>
                <a href="#" class="bg-red-500 text-white font-semibold py-3 px-6 rounded-lg hover:bg-purple-600 transition duration-300 text-center">Performance Reports</a>
                {{-- <a href="{{url('/help#partner')}}" class="bg-gray-300 text-dark-bg font-semibold py-3 px-6 rounded-lg hover:bg-gray-400 transition duration-300 text-center">Help Articles</a> --}}
                {{-- <a href="/community-forum" class="bg-orange-400 text-white font-semibold py-3 px-6 rounded-lg hover:bg-orange-500 transition duration-300 text-center">Community Forum</a> --}}
            </div>

            {{-- Added a logout form for proper functionality --}}
                <div class="mt-12 text-center border-t pt-8">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-primary-p">Logout of Admin panel</button>
                </form>
            </div>
        </div>
    </main>
@endsection
