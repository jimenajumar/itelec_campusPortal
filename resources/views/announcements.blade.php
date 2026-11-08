
@extends('layouts.app')
@section('title', 'Announcements - ACES')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-blue-950">
        School Announcements
    </h1>

    <p class="text-gray-500 mt-2">
        Stay informed about important school updates.
    </p>

    <div class="bg-white rounded-xl shadow-md p-6 mt-8">
        <h2 class="text-xl font-bold text-blue-950 mb-6">
            Latest Announcements
        </h2>

        <div id="announcementList" class="space-y-4">
            <p class="text-gray-500 text-center">
                Loading announcements...
            </p>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const list = document.getElementById('announcementList');
    const token = sessionStorage.getItem('campus_token');

    if (!token) {
        list.textContent = 'Please log in to view announcements.';
        return;
    }

    fetch('/api/announcements', {
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Unable to load announcements.');
        }
        return response.json();
    })
    .then(data => {
        list.innerHTML = '';

        const announcements = Array.isArray(data)
            ? data
            : (data.data || []);

        if (announcements.length === 0) {
            list.textContent = 'No announcements available.';
            return;
        }

        announcements.forEach(item => {
            const card = document.createElement('div');
            card.className = 'border border-gray-200 rounded-lg p-5';

            const title = document.createElement('h3');
            title.className = 'text-lg font-bold text-blue-950';
            title.textContent = item.title || 'Untitled Announcement';

            const content = document.createElement('p');
            content.className = 'text-gray-600 mt-3 whitespace-pre-line';
            content.textContent = item.content || item.message || '';

            const date = document.createElement('p');
            date.className = 'text-sm text-gray-400 mt-4';
            date.textContent = item.created_at
                ? new Date(item.created_at).toLocaleDateString()
                : '';

            card.append(title, content, date);
            list.appendChild(card);
        });
    })
    .catch(error => {
        list.textContent = error.message;
    });
});
</script>
@endsection
