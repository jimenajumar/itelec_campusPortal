@extends('layouts.app')
@section('title' , 'Weather - ACES')
@section('content')

<div class="max-7x1 mx-auto px-6 py-10">
    <h1 class="text-3x1 font-bold text-blue-950">
        Weather Updates
    </h1>
    <p class="text-gray-500 mt-2">
        Check the current weather conditions.
    </p>
    <div class="bg-white rounded-x1 shadow-md p-8 mt-8 text-center">
        <div class="text-6x1 mb-4">⛅</div>
        <h2 class="text-2x1 font-bold text-blue-950">Weather Information</h2>
        <p class="text-gray-500 mt-3">
            Weather updates will appear here
        </p>
    </div>
</div>
@endsection
