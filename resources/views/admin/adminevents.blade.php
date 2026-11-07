
@extends('layouts.admin')

@section('title', 'Events Management')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Events Management</h2>
    <p class="text-gray-500">View and manage submitted campus events.</p>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input
            type="text"
            id="searchEvent"
            placeholder="Search events..."
            class="border rounded-lg px-4 py-2 flex-1"
        >

        <select id="statusFilter" class="border rounded-lg px-4 py-2">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <div id="message" class="mb-4 text-sm"></div>

    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3">Event</th>
                    <th class="p-3">Venue</th>
                    <th class="p-3">Start Date</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Actions</th>
                </tr>
            </thead>

            <tbody id="eventsTable">
                <tr>
                    <td colspan="5" class="p-5 text-center text-gray-500">
                        Loading events...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
const token = sessionStorage.getItem('campus_token');
const table = document.getElementById('eventsTable');
const message = document.getElementById('message');

let eventsData = [];

function showMessage(text, success = false) {
    message.textContent = text;
    message.className = success
        ? 'mb-4 text-sm text-green-700'
        : 'mb-4 text-sm text-red-700';
}

function formatDate(date) {
    if (!date) return 'Not specified';

    const parsed = new Date(date);

    return isNaN(parsed.getTime())
        ? 'Invalid date'
        : parsed.toLocaleString();
}

async function loadEvents() {
    if (!token) {
        table.innerHTML = `
            <tr>
                <td colspan="5" class="p-5 text-center">
                    Login token not found. Please log in again.
                </td>
            </tr>`;
        return;
    }

    try {
        const response = await fetch('/api/events', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        if (!response.ok) {
            throw new Error('Unable to load events. Please check your login.');
        }

        eventsData = await response.json();
        renderEvents();

    } catch (error) {
        showMessage(error.message);
        table.innerHTML = '';
    }
}

function renderEvents() {
    const search = document.getElementById('searchEvent')
        .value.toLowerCase();

    const status = document.getElementById('statusFilter').value;

    const filtered = eventsData.filter(event => {
        const matchesSearch =
            (event.title || '').toLowerCase().includes(search) ||
            (event.venue || '').toLowerCase().includes(search);

        const matchesStatus =
            status === 'all' || event.status === status;

        return matchesSearch && matchesStatus;
    });

    table.replaceChildren();

    if (filtered.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');

        cell.colSpan = 5;
        cell.className = 'p-5 text-center text-gray-500';
        cell.textContent = 'No events found.';

        row.appendChild(cell);
        table.appendChild(row);
        return;
    }

    filtered.forEach(event => {
        const row = document.createElement('tr');
        row.className = 'border-b';

        const title = document.createElement('td');
        title.className = 'p-3 font-semibold';
        title.textContent = event.title || 'Untitled';

        const venue = document.createElement('td');
        venue.className = 'p-3';
        venue.textContent = event.venue || 'Not specified';

        const date = document.createElement('td');
        date.className = 'p-3';
        date.textContent = formatDate(event.starts_at);

        const statusCell = document.createElement('td');
        statusCell.className = 'p-3';

        const badge = document.createElement('span');
        badge.className = 'px-3 py-1 rounded-full text-sm font-semibold';

        const colors = {
            pending: 'bg-yellow-100 text-yellow-700',
            approved: 'bg-green-100 text-green-700',
            rejected: 'bg-red-100 text-red-700'
        };

        const currentStatus = event.status || 'pending';
        badge.className += ' ' + (colors[currentStatus] || 'bg-gray-100 text-gray-700');
        badge.textContent = currentStatus;

        statusCell.appendChild(badge);

        const actions = document.createElement('td');
        actions.className = 'p-3';

        if (currentStatus === 'pending') {
            const approve = document.createElement('button');
            approve.textContent = 'Approve';
            approve.className = 'bg-green-600 text-white px-3 py-2 rounded-lg mr-2 mb-1';
            approve.addEventListener('click', () => updateEvent(event.id, 'approve'));

            const reject = document.createElement('button');
            reject.textContent = 'Reject';
            reject.className = 'bg-red-600 text-white px-3 py-2 rounded-lg mb-1';
            reject.addEventListener('click', () => updateEvent(event.id, 'reject'));

            actions.append(approve, reject);
        } else {
            actions.textContent = 'No actions';
        }

        row.append(title, venue, date, statusCell, actions);
        table.appendChild(row);
    });
}

async function updateEvent(id, action) {
    try {
        const response = await fetch(`/api/events/${id}/${action}`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Unable to update event.');
        }

        showMessage(result.message, true);
        await loadEvents();

    } catch (error) {
        showMessage(error.message);
    }
}

document.getElementById('searchEvent')
    .addEventListener('input', renderEvents);

document.getElementById('statusFilter')
    .addEventListener('change', renderEvents);

loadEvents();
</script>

@endsection
