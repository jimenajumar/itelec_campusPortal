
@extends('layouts.app')
@section('title', 'Profile - ACES')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-blue-950">My Profile</h1>
    <p class="text-gray-500 mt-2">View your student information.</p>

    <div class="bg-white rounded-xl shadow-md p-8 mt-8">

        <div class="flex flex-col sm:flex-row items-center gap-6 mb-8">
            <div class="w-28 h-28 rounded-full bg-red-100 flex items-center justify-center text-5xl">
                👤
            </div>

            <div class="text-center sm:text-left">
                <h2 id="profileName" class="text-2xl font-bold text-blue-950">
                    Loading...
                </h2>
                <p class="text-gray-500">Student</p>
                <p class="text-gray-500">ACES Tagum College Inc.</p>
            </div>
        </div>

        <hr class="my-8">

        <h3 class="text-xl font-bold text-blue-950 mb-6">
            Student Information
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-500 text-sm">Student ID</p>
                <p id="profileStudentId" class="font-semibold">Loading...</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Email Address</p>
                <p id="profileEmail" class="font-semibold">Loading...</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Account Role</p>
                <p id="profileRole" class="font-semibold">Student</p>
            </div>
        </div>

        <p id="profileError" class="text-red-600 mt-6"></p>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const token = sessionStorage.getItem('campus_token');

    if (!token) {
        document.getElementById('profileError').textContent =
            'Please log in again to view your profile.';
        return;
    }

    fetch('/api/user', {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Unable to load profile information.');
        }
        return response.json();
    })
    .then(user => {
        document.getElementById('profileName').textContent =
            user.name || 'No name provided';

        document.getElementById('profileStudentId').textContent =
            user.student_id || 'Not provided';

        document.getElementById('profileEmail').textContent =
            user.email || 'Not provided';

        document.getElementById('profileRole').textContent =
            user.role || 'Student';
    })
    .catch(error => {
        document.getElementById('profileError').textContent =
            error.message;
    });
});
</script>
@endsection
