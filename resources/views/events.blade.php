
@extends(Auth::check() && Auth::user()->role === 'club_advisor'
    ? 'layouts.advisor'
    : 'layouts.app')
@section('title', 'School Events - ACES')
@section('content')

<div class="max-w-7xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-blue-950">
        School Events
    </h1>

    <p class="text-gray-500 mt-2">
        Stay updated with upcoming school activities
    </p>

    <div class="mt-8 space-y-10">

        <!-- Upcoming Events -->
        <section>
            <h2 class="text-xl font-bold text-blue-950 mb-4">
                Upcoming Events
            </h2>
            <div id="upcomingEvents"
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <p class="text-gray-500">Loading events...</p>
            </div>
        </section>

        <!-- Past Events -->
        <section>
            <h2 class="text-xl font-bold text-blue-950 mb-4">
                Past Events
            </h2>
            <div id="pastEvents"
                 class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            </div>
        </section>

    </div>
</div>

<script>
async function loadEventsPage() {
    const token = sessionStorage.getItem('campus_token');
    const upcomingEvents = document.getElementById('upcomingEvents');
    const pastEvents = document.getElementById('pastEvents');

    try {
        if (!token) {
            throw new Error('Login token not found. Please log in again.');
        }

        const response = await fetch('/api/events', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            throw new Error('Unable to load events. Status: ' + response.status);
        }

        const events = await response.json();
        const now = new Date();

        const upcoming = events.filter(event =>
            new Date(event.ends_at || event.starts_at) >= now
        ).sort((a, b) =>
            new Date(a.starts_at) - new Date(b.starts_at)
        );

        const past = events.filter(event =>
            new Date(event.ends_at || event.starts_at) < now
        ).sort((a, b) =>
            new Date(b.starts_at) - new Date(a.starts_at)
        );

        function createEventCard(event) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-xl shadow-md p-6 border border-gray-100';

            const isUpcoming = new Date(event.ends_at || event.starts_at) >= now;

            const badge = document.createElement('span');
            badge.className = isUpcoming
                ? 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700'
                : 'inline-block px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600';
            badge.textContent = isUpcoming ? 'Upcoming' : 'Past Event';

            const title = document.createElement('h3');
            title.className = 'text-xl font-bold text-blue-950 mt-3';
            title.textContent = event.title;

            const description = document.createElement('p');
            description.className = 'text-gray-600 mt-3';
            description.textContent = event.description || 'No description available.';

            const venue = document.createElement('p');
            venue.className = 'text-sm text-gray-500 mt-4';
            venue.textContent = 'Venue: ' + (event.venue || 'To be announced');

            const date = document.createElement('p');
            date.className = 'text-sm font-semibold text-red-800 mt-2';
            date.textContent = 'Date: ' + new Date(event.starts_at).toLocaleDateString();

            card.append(badge, title, description, venue, date);
            return card;
        }

        function displayEvents(container, list) {
            container.innerHTML = '';

            if (list.length === 0) {
                container.textContent = 'No events available.';
                container.classList.add('text-gray-500');
                return;
            }

            list.forEach(event => {
                container.appendChild(createEventCard(event));
            });
        }

        displayEvents(upcomingEvents, upcoming);
        displayEvents(pastEvents, past);

    } catch (error) {
        upcomingEvents.textContent = error.message;
        console.error('Events page error:', error);
    }
}

loadEventsPage();
</script>

@endsection
