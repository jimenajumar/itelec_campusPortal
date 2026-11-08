<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'ACES Student Portal')</title>
    @vite(['resources/css/app.css' , 'resources/js/app.js'])
</head>

<style>
    .slider-viewport {
        overflow: hidden;
        position: relative;
    }

    .slider-track {
        display: flex;
        transition: transform 0.7s ease-in-out;
        width: 100%;
    }

    .campus-slide {
        flex: 0 0 100%;
        width: 100%;
        min-width: 100%;
    }
</style>

<body class="bg-gray-100">
    <!-- Top Bar -->
    
<!-- Top Bar -->
    <div class="bg-red-800 text-white px-8 py-3 flex items-center justify-between">
        <div class="text-sm font-semibold">
            <span id="campusHeaderName">Campus Portal</span>
        </div>

        <div class="flex items-center gap-3">
            <div class="w-16 h-16 rounded-full bg-blue-800 text-white flex items-center justify-center text-2xl font-serif">
            ATCI
            </div>

            <div class="font-serif">
                <h1 id="headerStudentName" class="text-lg font-bold leading-tight">
                Loading...
                </h1>
                <p class="text-sm">IICT Department</p>
            </div>
        </div>
    </div>

     <!-- Navigation -->
      <nav class="bg-white shadow-md px-4 md:px-8 py-5 flex flex-wrap items-center justify-between gap-4">

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
            <form id="logoutForm" action="{{ url('/web-logout') }}" method="POST">
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
        // Load logged-in student's name
const campusToken = sessionStorage.getItem('campus_token');

if (campusToken) {
    fetch('/api/user', {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${campusToken}`
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Unable to load user.');
        return response.json();
    })
    .then(user => {
        document.getElementById('headerStudentName').textContent =
            user.name || 'Student';
    })
    .catch(() => {
        document.getElementById('headerStudentName').textContent =
            'Student';
    });
}

if (campusToken) {
    fetch('/api/user', {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${campusToken}`
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Unable to load user.');
        return response.json();
    })
    .then(user => {
        document.getElementById('headerStudentName').textContent =
            user.name || 'Student';
    })
    .catch(() => {
        document.getElementById('headerStudentName').textContent =
            'Student';
    });
}
const logoutForm = document.getElementById('logoutForm');

if (logoutForm) {
    logoutForm.addEventListener('submit', async function (event) {
        event.preventDefault();

        const form = this;
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

        form.submit();
    });
}
</script>
</body>
</html>
