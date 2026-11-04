@extends('layouts.app')
@section('title' , 'Rooms - ACES')
@section('content')
<div class=" max-w-6x1 mx-auto px-6 py-10">
    <h1 class="text-3x1 font-bold text-blue-950">Campus Rooms</h1>
    <p class="text-gray-500 mt-2">
        View available campus rooms and their details.
    </p>
    <div class="bg-white rounded-x1 shadow-md p-6 mt-8">
        <h2 class="text-x1 font-bold text-blue-950 mb-3">Available Rooms</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border rounded-x1 p-5">
                <h3 class="text-lg font-bold text-blue-950">Room 101</h3>
                <p class="text-gray-500 mt-2">
                    Building A . First floor
                </p>
                <p class="text-sm text-green-700 font-semibold mt-3">
                    Status: Available
                </p>
                <button
                onclick="document.getElementById('roomDetails').classList.toggle('hidden')"
                class="mt-4 bg-red-800 text-white px-5 py-2 rounded-lg">
                View Details
                </button>
                <div id="roomDetails" class="hidden mt-4 p-4 bg-gray-100 rounded-lg">
                    <h4 class="font-bold text-blue-950">
                        Room 101 Details
                    </h4>
                    <p class="text-gray-60 mt-2">
                        Building: A
                    </p>
                    <p class="text-gray-600">
                        Floor: First Floor
                    </p>
                    <p class="text-gra-600">
                        Status: Available
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-x1 shadow-md p-6 mt-10">
        <h2 class="text-x1 font-bold text-blue-950 mb-3">
            My Room Assignments
        </h2>
        <p class="text-gray-500 mb-5">
            View the rooms assigned to your classes.
        </p>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-blue-950 text-white">
                        <th class="p-4">Room</th>
                        <th class="p-4">Subject</th>
                        <th class="p-4">Day</th>
                        <th class="p-4">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b">
                        <td class="p-4"Room 101></td>
                        <td class="p-4"Sample Subject></td>
                        <td class="p-4"monday></td>
                        <td class="p-4"8;00 AM - 9:00 AM></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
