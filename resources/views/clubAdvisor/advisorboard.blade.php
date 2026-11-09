@extends('layouts.advisor')

@section('title', 'Club Advisor Dashboard - ACES')

@section('content')

<div class="min-h-screen bg-gray-50">

    <!-- Welcome Banner -->
    <section class="bg-red-900 text-white px-6 py-12">
        <div class="max-w-7xl mx-auto">
            <p class="text-red-200 text-sm font-semibold uppercase tracking-wider">
                ACES Tagum College Inc.
            </p>

            <h1 class="text-3xl md:text-4xl font-bold mt-2">
                Club Advisor Dashboard
            </h1>

            <p class="text-red-100 mt-3">
                Welcome, {{ Auth::user()->name }}!
                Manage your assigned clubs and student activities.
            </p>
        </div>
    </section>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-6 py-10">

        <h2 class="text-2xl font-bold text-blue-950 mb-6">
            Club Management
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ url('/club-advisor/advisorclub') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">My Clubs</h3>
                <p class="text-gray-500 text-sm mt-2">
                    View and manage your assigned clubs.
                </p>
            </a>

            <a href="{{ url('/club-advisor/memberships') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Membership Requests</h3>
                <p class="text-gray-500 text-sm mt-2">
                    Review membership requests for your clubs.
                </p>
            </a>

            <a href="{{ url('/events') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Campus Events</h3>
                <p class="text-gray-500 text-sm mt-2">
                    View approved campus events.
                </p>
            </a>

            <a href="{{ url('/announcements') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Announcements</h3>
                <p class="text-gray-500 text-sm mt-2">
                    Read published campus announcements.
                </p>
            </a>

            <a href="{{ url('/room-assignments') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Room Assignments</h3>
                <p class="text-gray-500 text-sm mt-2">
                    View your campus room assignments.
                </p>
            </a>

            <a href="{{ url('/profile') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">My Profile</h3>
                <p class="text-gray-500 text-sm mt-2">
                    View your account information.
                </p>
            </a>

        </div>

    </div>
</div>

@endsection