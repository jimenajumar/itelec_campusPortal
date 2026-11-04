<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ACES Student Portal')</title>
    @vite(['resources/css/app.css' , 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <!-- Top Bar -->
    <div class="bg-red-800 text-white px-8 py-3 text-right -text-sm">Campus Portal
     </div>
     <!-- Navigation -->
      <nav class="bg-white shadow-md px-4 md:px-8 py-5 flex flex-wrap items-center justify-between gap-4">
        <!-- School Logo -->
         <div class="flex items-center gap-3">
            <div class="w-16 h-16 rounded-full bg-red-800 text-white flex items-center justify-center text-2x1 font-serif">
                ATCI
            </div>
            <div class="text-blue-950 font-serif">
                <h1 class="text-lg font-bold leading-tight">
                    Evander Dayoc
                </h1>
                <p class="text-sm">IICT Department</p>
            </div>
         </div>
         <!-- Navigation Links -->
          <div class="flex flex-wrap items-center justify-center gap-2 text-blue-950 font-semibold">
            <a href="{{ url('/dashboard')}}" class="bg-red-700 text-white rounded-full px-5 py-3 hover:bg-red-800 transition">Dashboard</a>
            <a href="{{ url('/schedule') }}" class="px-4 py-3 hover:text-red-700 transition">Schedule</a>
            <a href="{{ url('/events') }}" class="px-4 py-3 hover:text-red-700 transition">Events</a>
            <a href="{{ url('/announcements') }}" class="px-4 py-3 hover:text-red-700 transition">Announcements</a>
            <a href="{{ url('/weather') }}" class="px-4 py-3 hover:text-red-700 transition">Weather</a>
            <a href="{{ url('/profile') }}" class="px-4 py-3 hover:text-red-700 transition">Profile</a>
            <a href="{{ url('/clubs') }}" class="px-4 py-3 hover:text-red-700 transition">Clubs</a>
            <a href="{{ url('/rooms') }}" class="px-4 py-3 hover:text-red-700 transition">Rooms</a>
            <a href="/logout" class="border border-red-700 text-red-700 rounded-full px-5 py-3 hover:bg-red-700 hover:text-white transition">Logout</a>
          </div>
      </nav>
      <!-- Page Content -->
      @yield('content')
</body>
</html>
