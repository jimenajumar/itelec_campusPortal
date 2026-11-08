<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'ACES Admin Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Admin Header -->
    <header class="bg-red-800 text-white px-8 py-5">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-white text-red-800
                        flex items-center justify-center font-bold text-xl">
                ATCI
            </div>

            <div>
                <h1 class="text-xl font-bold">
                    ACES Tagum College Inc.
                </h1>
                <p class="text-sm text-red-100">
                    Administration Portal
                </p>
            </div>
        </div>
    </header>

    <!-- Admin Navigation -->
    <nav class="bg-white shadow-md px-6 py-4">
        <div class="flex flex-wrap gap-3 font-semibold text-blue-950">

            <a href="{{ url('/admin/dashboard') }}"
               class="rounded-lg bg-red-700 text-white px-4 py-3">
                Dashboard
            </a>

            <a href="{{ url('/admin/users') }}"
               class="px-4 py-3 hover:text-red-700">
                Users
            </a>

            <a href="{{ url('/admin/events') }}"
               class="px-4 py-3 hover:text-red-700">
                Events
            </a>

            <a href="{{ url('/admin/announcements') }}"
               class="px-4 py-3 hover:text-red-700">
                Announcements
            </a>

            <a href="{{ url('/admin/clubs') }}"
               class="px-4 py-3 hover:text-red-700">
                Clubs
            </a>

            <a href="{{ url('/admin/rooms') }}"
               class="px-4 py-3 hover:text-red-700">
                Rooms
            </a>
            <a href="{{ url('/admin/room-assignments') }}" class="px-4 py-3 hover:text-red-700">Room Assignments</a>
            <a href="/admin/memberships"
            class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100">
            <span>Club Memberships</span>
            </a>
            <form id="logoutForm" action="{{ url('/web-logout') }}"     method="POST" class="ml-auto">
                @csrf
                <button type="submit"
                class="border border-red-700 text-red-700 rounded-lg px-4 py-3 hover:bg-red-700 hover:text-white">
                    Logout
                </button>
            </form>

        </div>
    </nav>

    <!-- Admin Page Content -->
    <main class="px-6 py-8">
        @yield('content')
    </main>
    <script>
document.getElementById('logoutForm').addEventListener('submit', async function () {
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
});
</script>

</body>
</html>
