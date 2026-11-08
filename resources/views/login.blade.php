<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - ACES</title>
    @vite(['resources/css/app.css' , 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="bg-white w-full max-w-md p-8 rounded-2xl shadow-lg">

        <div class="text-center mb-6">
            <img src="{{ asset('images/logo.png') }}"
                 alt="ACES Logo"
                 class="w-20 h-20 mx-auto object-contain">

            <h1 class="text-2xl font-bold text-blue-950 mt-4">
                Student Login
            </h1>

            <p class="text-gray-500 mt-2">
                Welcome to ACES Student Portal
            </p>
        </div>
        <form id="loginForm"
        class="space-y-5">
        <div id="loginMessage"
        class="text-sm text-center"></div>
            <div>
                <label class="block text-gray-700 fot-semibold mb-2">
                    Email Address
                </label>
                <input type="email"
                name="email"
                placeholder="Enter your email"
                class="w-full border border-gray-300 rounded-lg-px-4 py-3 focus:ring-2 focus:ring-red-700 outline-none">
            </div>
            <div>
                <label class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password"
                name="password"
                placeholder="Enter your password"
                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-red-700 outline-none">
            </div>
            <button type="submit"
            class="w-full bg-red-800 text-white py-3 rounded-lg font-semibold hover:bg-red-900 transition">Log in
            </button>
        </form>


    </div>
</div>
<script>
    document.getElementById('loginForm').addEventListener('submit', async function(e){
        e.preventDefault();
        const email = document.querySelector('[name="email"]').value;
        const password = document.querySelector('[name="password"]').value;
        const message = document.getElementById('loginMessage');
        try {
            const response = await fetch('/web-login', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({email, password})
            });
            console.log('API response:', response.status);
            const data = await response.json();
            if (!response.ok) {
                message.textContent = data.message || 'Login failed.';
                message.className = 'text-sm text-center text-red-600';
                return;
            }
            message.textContent = 'Login successful!';
            message.className = 'text-sm text-center text-green-600';

            // Save the API token for this browser session
            sessionStorage.setItem('campus_token', data.token);
            sessionStorage.setItem('campus_user', JSON.stringify(data.user));
            console.log('Saved token:', sessionStorage.getItem('campus_token'));
            console.log('Logged-in user:', data.user);

            window.location.href = data.redirect;
            // secure the login session.
        } catch(error) {
            message.textContent = 'Unable to connect to the server.';
            message.className = 'text-sm text-center text-red-600';
        }
    });
</script>
</body>
</html>
