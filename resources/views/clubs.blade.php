@extends('layouts.app')
@section('title' , 'Clubs - ACES')
@section('content')
<div class="max-w-6x1 mx-auto px-6 py-10">
    <h1 class="text-3x1 font-bold text-blue-950">Campus Clubs</h1>
    <p class="text-gray-500 mt-2">
        Explore campus organizations and find a club to join.
    </p>
    <div class="bg-white rounded-x1 shadow-md p-6 mt-8">
        <h2 class="text-x1 font-bold text-blue-950 mb-3">Available Clubs</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="border rounded-x1 p-5">
                <h3 class="text-lg font-bold text-blue-950">Club Name</h3>
                <p class="text-gray-500 mt-2">
                    Club description will appear here.
                </p>
                <p class="text-sm text-green-500 font-semibold mt-3">Membership open
                </p>
                <button
                onclick="document.getElementById('clubDetails').classList.toggle('hidden')"
                class="mt-4 bg-red-800 text-white px-5 py-2 rounded-lg">
                    View Details
                </button>
                <div id="clubDetails" class="hidden mt-4 p-4 bg-gray-100 rounded-lg">
                    <h4 class="font-bold text-blue-950">
                        Club Information
                    </h4>
                    <p class="text-gray-600 mt-2">
                        Club Name: Sample Club
                    </p>
                    <p class="text-gray-600">
                        Membership: Open
                    </p>
                    <p class="text-gray-600">
                        Membership requests are subject to approval.
                    </p>
                    <button class="mt-3 bg-red-800 text-white px-4 py-2 rounded-lg">
                        Request Membership
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
