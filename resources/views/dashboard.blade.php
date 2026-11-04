@extends('layouts.app')
@section('title', 'Dashboard - ACES')
@section('content')
      <!-- Banner -->
      <section class="relative min-h-[450px] flex items-center overflow-hidden bg-blue-950">
        <!-- Campus Background -->
        <div class="absolute inset-0">
            <img src="{{  asset('images/aces building.png') }}"
            alt="ACES Tagum College Campus"
            class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-blue-950/70"></div>
        </div>
        <!-- Hero Content -->
        <div class="relative z-10 px-8 md:px-16 py-20 text-white">
            <h1 class="text-4x1 md:text-6x1 font-bold">WELCOME BACK</h1>
            <div class="w-20 h-1 bg-red-600 my-5"></div>
            <p class="text-lg md:text-x1 tracking-wider">
                Your Student Journey Starts Here.
            </p>
            <span class="inline-block mt-7 bg-red-700 px-6 py-3 rounded-ful font-semibold">Student Dashboard</span>
        </div>
        <!-- Curved Bottom Accent-->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none">
            <svg viewBox="0 0 1440 160"
            class="w-full h-20 md:h-28"
            preserveAspectRatio="none">
            <!-- Red wave -->
            <path d="M0,75 C350, 145 850,10 1440,65 L1440,160 L0,160 Z"
            fill="#991b1b"/>
            <!-- white Wave -->
            <path d="M0,105 C400, 165 950,45 1440,95 L1440,160 L0,160 Z"
            fill="white"/>
        </div>
      </section>

      <!-- Dashboard Cards -->
      <section class="bg-gray-100 px-6 md:px-12 py-12">
        <div class="max-w-7x1 mx-auto mb-8">
            <h2 class="text-3x1 font-bold text-blue-950 text-center">
                Your Student Dashboard
            </h2>
            <p class="text-gray=500 mt-2 text-center">
                Everything you need for your school day
            </p>
        </div>
        <div class="max-w-7x1 mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Class Schedule -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-red-700">
                <div class="text-4x1 mb-4">📅</div>
                <h2 class="text-x1 font-bold text-blue-950">Class Schedule</h2>
                <p class="text-gray-500 mt-2">Check upcoming classes</p>
                <a href="{{ url('/schedule')}}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">View Schedule 👉</a>
            </div>
            <!-- Weather -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-blue-900">
                <div class="text-4x1 mb-4">⛅</div>
                <h2 class="text-x1 font-bold text-blue-950">Weather</h2>
                <p class="text-gray-500 mt-2">Check the latest weather</p>
                <a href="{{ url('/weather') }}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">View Weather 👉</a>

            </div>
            <!-- School Events -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-red-700">
                <div class="text-4x1 mb-4">🥳</div>
                <h2 class="text-x1 font-bold text-blue-950">School Events</h2>
                <p class="text-gray-500 mt-2">Stay updated with school activities</p>
                <a href="{{ url('/events') }}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">View Events 👉</a>
            </div>
            <!-- announcements -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-blue-900">
                <div class="text-4x1 mb-4">🔔</div>
                <h2 class="text-x1 font-bold text-blue-950">Announcements</h2>
                <p class="text-gray-500 mt-2">Keep track of important announcements.</p>
                <a href="{{ url('/announcements') }}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">
                    View announcements 👉</a>
            </div>
            <!-- Clubs Cards -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-red-900">
                <div class="text-4x1 mb-4">🏫</div>
                <h2 class="text-x1 font-bold text-blue-950">Clubs</h2>
                <p class="text-gray-500 mt-2">Explore clubs and request membership.</p>
                <a href="{{ url('/clubs') }}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">
                    View Clubs 👉</a>
            </div>
            <!-- Rooms -->
            <div class="bg-white rounded-x1 shadow-md p-6 border-t-4 border-blue-900">
                <div class="text-4x1 mb-4">🚪</div>
                <h2 class="text-x1 font-bold text-blue-950">Rooms</h2>
                <p class="text-gray-500 mt-2">View campus rooms and room information.</p>
                <a href="{{ url('/rooms') }}" class="inline-block mt-5 text-red-700 font-semibold hover:underline">
                    View Rooms 👉</a>
            </div>
        </div>
      </section>

</body>
</html>
@endsection
