<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'ACES Club Advisor Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- Top Bar -->
    <div class="bg-red-800 text-white px-8 py-3 flex items-center justify-between">

        <div class="text-sm font-semibold">
            ACES Tagum College Inc.
        </div>

        <div class="flex items-center gap-3">

            <div class="w-16 h-16 rounded-full bg-blue-800 text-white flex items-center justify-center text-2xl font-serif">
                ATCI
            </div>

            <div class="font-serif">
                <h1 class="text-lg font-bold leading-tight">
                    {{ Auth::user()->name }}
                </h1>
                <p class="text-sm">Club Advisor</p>
            </div>

        </div>
    </div>

    <!-- Advisor Navigation -->
    <nav class="bg-white shadow-md px-4 md:px-8 py-5">

        <div class="flex flex-wrap items-center gap-2 text-blue-950 font-semibold">

            <a href="{{ url('/club-advisor/dashboard') }}"
               class="bg-red-700 text-white rounded-full px-5 py-3 hover:bg-red-800 transition">
                Dashboard
            </a>

            <a href="{{ url('/club-advisor/clubs') }}"
               class="px-4 py-3 hover:text-red-700 transition">
                My Clubs
            </a>
            <a href="{{ url('/club-advisor/memberships') }}"
            class="px-4 py-3 hover:text-red-700 transition">
            Membership Requests
            </a>

            <a href="{{ url('/events') }}"
               class="px-4 py-3 hover:text-red-700 transition">
                Events
            </a>

            <a href="{{ url('/announcements') }}"
               class="px-4 py-3 hover:text-red-700 transition">
                Announcements
            </a>

            <a href="{{ url('/room-assignments') }}"
               class="px-4 py-3 hover:text-red-700 transition">
                Room Assignments
            </a>

            <a href="{{ url('/profile') }}"
               class="px-4 py-3 hover:text-red-700 transition">
                Profile
            </a>

            <form id="advisorLogoutForm"
                  action="{{ url('/web-logout') }}"
                  method="POST">
                @csrf

                <button type="submit"
                    class="border border-red-700 text-red-700 rounded-lg px-4 py-3 hover:bg-red-700 hover:text-white">
                    Logout
                </button>
            </form>

        </div>
    </nav>

    <!-- Page Content -->
    @yield('content')

    <script>
        const advisorLogoutForm =
            document.getElementById('advisorLogoutForm');

        advisorLogoutForm.addEventListener('submit', async function(event) {
            event.preventDefault();

            const token = sessionStorage.getItem('campus_token');

            if (token) {
                try {
                    await fetch('/api/logout', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${token}`
                        }
                    });
                } catch (error) {
                    console.error('API logout request failed.');
                }
            }

            sessionStorage.removeItem('campus_token');
            sessionStorage.removeItem('campus_user');

            this.submit();
        });
    </script>

</body>
</html>
