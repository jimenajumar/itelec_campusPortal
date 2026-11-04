@extends('layouts.app')
@section('title' , 'Profile - ACES')
@section('content')

<div class="max-w-5x1 mx-auto px-6 py-10">
    <h1 class="text-3x1 font-bold text-blue-950">
        My Profile
    </h1>
    <p class="text-gray-500 mt-2">
        View your student information
    </p>
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <div class="w-28 h-28 rounded-full bg-gray-200 flex items-center justify-center text-5x1">🅿</div>
        <div class="text-center sm:text-left">
            <h2 class="text-2x1 font-bold text-blue-950">
                Student name
            </h2>
            <p class="text-gray-500">Student</p>
            <p class="text-gray-500">ACES Tagum College Inc.</p>
        </div>
        <hr class="my-8">
        <h3 class="text-x1 font-bold text-blue-950 mb-5">
            Student Information
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 text-sm">Student ID</p>
                <p class="font-semibold">Not provided</p>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Course / Strand</p>
                <p class="font-semibold">Not provided</p>
            </div>
        </div>
    </div>
</div>
@endsection
