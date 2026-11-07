
@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Activity Logs</h2>
    <p class="text-gray-500">Monitor actions performed in the Campus Portal.</p>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input
            type="text"
            id="searchLog"
            placeholder="Search activities or users..."
            class="border rounded-lg px-4 py-2 flex-1"
        >

        <select id="actionFilter" class="border rounded-lg px-4 py-2">
            <option value="all">All Actions</option>
            <option value="created">Created</option>
            <option value="updated">Updated</option>
            <option value="deleted">Deleted</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <p id="message" class="text-sm mb-4"></p>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 text-blue-950">
                    <th class="p-3">User</th>
                    <th class="p-3">Action</th>
                    <th class="p-3">Module</th>
                    <th class="p-3">Description</th>
                    <th class="p-3">Date</th>
                </tr>
            </thead>

            <tbody id="logList">
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">
                        Loading activity logs...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<script>
const token = sessionStorage.getItem('campus_token');
const logList = document.getElementById('logList');
const message = document.getElementById('message');

let logs = [];

async function loadLogs() {
    try {
        if (!token) {
            throw new Error('Login token not found. Please log in again.');
        }

        const response = await fetch('/api/activity-logs', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Unable to load activity logs.');
        }

        logs = result.data || [];
        renderLogs();

    } catch (error) {
        message.textContent = error.message;
        message.className = 'text-sm mb-4 text-red-700';
    }
}

function renderLogs() {
    const search = document.getElementById('searchLog')
        .value.toLowerCase();

    const action = document.getElementById('actionFilter').value;

    const filtered = logs.filter(log => {
        const user = log.user?.name || 'Unknown User';

        const text = [
            user,
            log.action,
            log.module,
            log.description
        ].join(' ').toLowerCase();

        return text.includes(search) &&
            (action === 'all' || log.action === action);
    });

    logList.replaceChildren();

    if (filtered.length === 0) {
        const row = document.createElement('tr');
        const cell = document.createElement('td');

        cell.colSpan = 5;
        cell.className = 'p-4 text-center text-gray-500';
        cell.textContent = 'No activity logs found.';

        row.appendChild(cell);
        logList.appendChild(row);
        return;
    }

    filtered.forEach(log => {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50';

        const values = [
            log.user?.name || 'Unknown User',
            log.action || 'N/A',
            log.module || 'N/A',
            log.description || 'No description',
            log.created_at
                ? new Date(log.created_at).toLocaleString()
                : 'N/A'
        ];

        values.forEach(value => {
            const cell = document.createElement('td');
            cell.className = 'p-3 text-sm text-gray-700';
            cell.textContent = value;
            row.appendChild(cell);
        });

        logList.appendChild(row);
    });
}

document.getElementById('searchLog')
    .addEventListener('input', renderLogs);

document.getElementById('actionFilter')
    .addEventListener('change', renderLogs);

loadLogs();
</script>

@endsection
