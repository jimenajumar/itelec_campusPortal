
@extends('layouts.admin')

@section('title', 'Room Assignments')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Room Assignments</h2>
    <p class="text-gray-500">Schedule and manage campus room reservations.</p>
</div>

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 id="formTitle" class="text-lg font-bold mb-4">Create Room Assignment</h3>

    <form id="assignmentForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <input type="hidden" id="assignmentId">

        <input id="title" required placeholder="Assignment Title"
            class="border rounded-lg px-4 py-3">

        <select id="roomId" required class="border rounded-lg px-4 py-3">
            <option value="">Select Room</option>
        </select>

        <select id="assignmentType" required class="border rounded-lg px-4 py-3">
            <option value="">Select Assignment Type</option>
            <option value="class">Class</option>
            <option value="event">Event</option>
            <option value="meeting">Meeting</option>
            <option value="other">Other</option>
        </select>

        <div>
            <label class="block text-sm text-gray-600 mb-1">Start Date and Time</label>
            <input id="startsAt" type="datetime-local" required
                class="border rounded-lg px-4 py-3 w-full">
        </div>

        <div>
            <label class="block text-sm text-gray-600 mb-1">End Date and Time</label>
            <input id="endsAt" type="datetime-local" required
                class="border rounded-lg px-4 py-3 w-full">
        </div>

        <div class="flex gap-2 md:col-span-2">
            <button type="submit" id="saveButton"
                class="bg-red-800 text-white px-5 py-2 rounded-lg">
                Create Assignment
            </button>

            <button type="button" id="cancelButton"
                class="hidden bg-gray-500 text-white px-5 py-2 rounded-lg">
                Cancel
            </button>
        </div>
    </form>

    <p id="message" class="mt-3 text-sm"></p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input id="searchAssignment" placeholder="Search assignments..."
            class="border rounded-lg px-4 py-2 flex-1">

        <select id="typeFilter" class="border rounded-lg px-4 py-2">
            <option value="all">All Types</option>
            <option value="class">Class</option>
            <option value="event">Event</option>
            <option value="meeting">Meeting</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div id="assignmentList" class="space-y-4">
        <p class="text-gray-500">Loading assignments...</p>
    </div>
</div>

<script>
const token = sessionStorage.getItem('campus_token');

const form = document.getElementById('assignmentForm');
const assignmentList = document.getElementById('assignmentList');
const message = document.getElementById('message');

let assignments = [];

async function apiRequest(url, options = {}) {
    if (!token) {
        throw new Error('Login token not found. Please log in again.');
    }

    const response = await fetch(url, {
        ...options,
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${token}`,
            ...options.headers
        }
    });

    const result = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(result.message || 'Request failed.');
    }

    return result;
}

function showMessage(text, success = false) {
    message.textContent = text;
    message.className = success
        ? 'mt-3 text-sm text-green-700'
        : 'mt-3 text-sm text-red-700';
}

async function loadRooms() {
    const result = await apiRequest('/api/rooms');
    const rooms = result.data || [];
    const select = document.getElementById('roomId');

    select.replaceChildren();

    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = 'Select Room';
    select.appendChild(placeholder);

    rooms.forEach(room => {
        const option = document.createElement('option');
        option.value = room.id;
        option.textContent =
            `${room.room_number} - ${room.room_name || 'Unnamed Room'}`;
        select.appendChild(option);
    });
}

async function loadAssignments() {
    try {
        const result = await apiRequest('/api/room-assignments');
        assignments = result.data || [];
        renderAssignments();
    } catch (error) {
        assignmentList.textContent = error.message;
    }
}

function formatDate(date) {
    if (!date) return 'Not specified';

    return new Date(date).toLocaleString();
}

function renderAssignments() {
    const search = document.getElementById('searchAssignment')
        .value.toLowerCase();

    const type = document.getElementById('typeFilter').value;

    const filtered = assignments.filter(item => {
        const roomName = item.room?.room_number || '';

        const matchesSearch =
            (item.title || '').toLowerCase().includes(search) ||
            roomName.toLowerCase().includes(search);

        return matchesSearch &&
            (type === 'all' || item.assignment_type === type);
    });

    assignmentList.replaceChildren();

    if (!filtered.length) {
        const empty = document.createElement('p');
        empty.className = 'text-center text-gray-500 py-6';
        empty.textContent = 'No room assignments found.';
        assignmentList.appendChild(empty);
        return;
    }

    filtered.forEach(item => {
        const card = document.createElement('div');
        card.className = 'border rounded-xl p-5';

        const title = document.createElement('h3');
        title.className = 'text-lg font-bold text-blue-950';
        title.textContent = item.title;

        const details = document.createElement('p');
        details.className = 'text-gray-600 mt-2';
        details.textContent =
            `Room: ${item.room?.room_number || 'Unknown'} | Type: ${item.assignment_type}`;

        const schedule = document.createElement('p');
        schedule.className = 'text-gray-500 mt-2';
        schedule.textContent =
            `Start: ${formatDate(item.starts_at)} | End: ${formatDate(item.ends_at)}`;

        const status = document.createElement('p');
        status.className = 'text-sm font-semibold text-green-700 mt-2';
        status.textContent = `Status: ${item.status || 'scheduled'}`;

        const actions = document.createElement('div');
        actions.className = 'flex gap-2 mt-4';

        const edit = document.createElement('button');
        edit.textContent = 'Edit';
        edit.className = 'bg-blue-700 text-white px-4 py-2 rounded-lg';
        edit.addEventListener('click', () => editAssignment(item));

        const remove = document.createElement('button');
        remove.textContent = 'Delete';
        remove.className = 'bg-red-700 text-white px-4 py-2 rounded-lg';
        remove.addEventListener('click', () => deleteAssignment(item));

        actions.append(edit, remove);
        card.append(title, details, schedule, status, actions);
        assignmentList.appendChild(card);
    });
}

function toLocalDateTime(value) {
    if (!value) return '';

    const date = new Date(value);
    const offset = date.getTimezoneOffset() * 60000;

    return new Date(date.getTime() - offset)
        .toISOString()
        .slice(0, 16);
}

form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const id = document.getElementById('assignmentId').value;

    const data = {
        room_id: document.getElementById('roomId').value,
        title: document.getElementById('title').value,
        assignment_type: document.getElementById('assignmentType').value,
        starts_at: document.getElementById('startsAt').value,
        ends_at: document.getElementById('endsAt').value
    };

    try {
        const result = await apiRequest(
            id ? `/api/room-assignments/${id}` : '/api/room-assignments',
            {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            }
        );

        showMessage(result.message, true);
        resetForm();
        await loadAssignments();

    } catch (error) {
        showMessage(error.message);
    }
});

function editAssignment(item) {
    document.getElementById('assignmentId').value = item.id;
    document.getElementById('title').value = item.title;
    document.getElementById('roomId').value = item.room_id;
    document.getElementById('assignmentType').value = item.assignment_type;
    document.getElementById('startsAt').value = toLocalDateTime(item.starts_at);
    document.getElementById('endsAt').value = toLocalDateTime(item.ends_at);

    document.getElementById('formTitle').textContent = 'Edit Room Assignment';
    document.getElementById('saveButton').textContent = 'Update Assignment';
    document.getElementById('cancelButton').classList.remove('hidden');

    form.scrollIntoView({ behavior: 'smooth' });
}

function resetForm() {
    form.reset();

    document.getElementById('assignmentId').value = '';
    document.getElementById('formTitle').textContent = 'Create Room Assignment';
    document.getElementById('saveButton').textContent = 'Create Assignment';
    document.getElementById('cancelButton').classList.add('hidden');
}

document.getElementById('cancelButton')
    .addEventListener('click', resetForm);

async function deleteAssignment(item) {
    if (!confirm(`Delete "${item.title}"?`)) return;

    try {
        const result = await apiRequest(
            `/api/room-assignments/${item.id}`,
            { method: 'DELETE' }
        );

        showMessage(result.message, true);
        await loadAssignments();

    } catch (error) {
        showMessage(error.message);
    }
}

document.getElementById('searchAssignment')
    .addEventListener('input', renderAssignments);

document.getElementById('typeFilter')
    .addEventListener('change', renderAssignments);

async function initializePage() {
    try {
        await loadRooms();
        await loadAssignments();
    } catch (error) {
        showMessage(error.message);
    }
}

initializePage();
</script>

@endsection
