@extends('layouts.admin')

@section('title', 'Admin Dashboard - ACES')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-950">
            Admin Dashboard
        </h1>

        <p class="text-gray-500 mt-2">
            Welcome to the ACES Tagum College Inc. Administration Portal.
        </p>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-red-700">
            <p class="text-gray-500 text-sm">Total Students</p>
            <h2 class="text-3xl font-bold text-blue-950 mt-2">{{ $totalStudents }}</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-700">
            <p class="text-gray-500 text-sm">Pending Events</p>
            <h2 class="text-3xl font-bold text-blue-950 mt-2">{{ $pendingEvents}}</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-600">
            <p class="text-gray-500 text-sm">Announcements</p>
            <h2 class="text-3xl font-bold text-blue-950 mt-2">{{ $totalAnnouncements }}</h2>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-600">
            <p class="text-gray-500 text-sm">Total Clubs</p>
            <h2 class="text-3xl font-bold text-blue-950 mt-2">{{ $totalClubs }}</h2>
        </div>

    </div>

    <!-- Management Section -->
    <div class="mt-10">
        <h2 class="text-xl font-bold text-blue-950 mb-5">
            Management Center
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <a href="{{ url('/admin/users') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">User Management</h3>
                <p class="text-gray-500 text-sm mt-2">Manage student and staff accounts.</p>
            </a>

            <a href="{{ url('/admin/events') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Events Management</h3>
                <p class="text-gray-500 text-sm mt-2">Review and manage campus events.</p>
            </a>

            <a href="{{ url('/admin/announcements') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Announcements</h3>
                <p class="text-gray-500 text-sm mt-2">Publish and manage announcements.</p>
            </a>

            <a href="{{ url('/admin/clubs') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Club Management</h3>
                <p class="text-gray-500 text-sm mt-2">Manage campus organizations.</p>
            </a>

            <a href="{{ url('/admin/rooms') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Room Management</h3>
                <p class="text-gray-500 text-sm mt-2">Manage classrooms and facilities.</p>
            </a>

            <a href="{{ url('/admin/room-assignments') }}"
               class="bg-white p-6 rounded-xl shadow-sm hover:shadow-md transition">
                <h3 class="font-bold text-lg text-blue-950">Room Assignments</h3>
                <p class="text-gray-500 text-sm mt-2">View classroom assignments.</p>
            </a>
            <a href="{{ url('/admin/activity-logs') }}" class="px-4 py-3 hover:text-red-700">Activity Logs</a>

        </div>
    </div>

</div>

@endsection
