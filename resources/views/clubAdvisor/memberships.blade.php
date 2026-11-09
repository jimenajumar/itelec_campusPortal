@extends('layouts.advisor')

@section('title', 'My Club Membership Requests')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">My Club membership Requests</h2>
    <p class="text-gray-500">Review and manage student club membership requests.</p>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input
            type="text"
            id="searchMembership"
            placeholder="Search student or club..."
            class="border rounded-lg px-4 py-2 flex-1"
        >

        <select id="membershipFilter" class="border rounded-lg px-4 py-2">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
    </div>

    <div id="membershipMessage" class="mb-4 text-sm"></div>

    <div id="membershipList" class="space-y-4">
        <p class="text-gray-500">Loading membership requests...</p>
    </div>

</div>

<script>
const membershipToken = sessionStorage.getItem('campus_token');
const membershipList = document.getElementById('membershipList');
const membershipMessage = document.getElementById('membershipMessage');

let membershipRecords = [];

function membershipNotice(message, success = false) {
    membershipMessage.textContent = message;
    membershipMessage.className = success
        ? 'mb-4 text-sm text-green-700'
        : 'mb-4 text-sm text-red-700';
}

async function membershipApi(url, options = {}) {
    if (!membershipToken) {
        throw new Error('Login token not found. Please log in again.');
    }

    const response = await fetch(url, {
        ...options,
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${membershipToken}`,
            ...options.headers
        }
    });

    const result = await response.json().catch(() => ({}));

    if (!response.ok) {
        throw new Error(result.message || 'Something went wrong.');
    }

    return result;
}

async function loadMemberships() {
    try {
        membershipList.innerHTML =
            '<p class="text-gray-500">Loading membership requests...</p>';

        const clubs = await membershipApi('/api/clubs');

        const results = await Promise.all(
            clubs.map(async club => {
                const memberships = await membershipApi(
                    `/api/clubs/${club.id}/memberships`
                );

                return memberships.map(membership => ({
                    ...membership,
                    club_name: club.name
                }));
            })
        );

        membershipRecords = results.flat();
        renderMemberships();

    } catch (error) {
        membershipNotice(error.message);
        membershipList.replaceChildren();
    }
}

function renderMemberships() {
    const search = document.getElementById('searchMembership')
        .value.toLowerCase();

    const status = document.getElementById('membershipFilter').value;

    const filtered = membershipRecords.filter(record => {
        const studentName = record.student?.name || '';
        const matchesSearch =
            studentName.toLowerCase().includes(search) ||
            record.club_name.toLowerCase().includes(search);

        return matchesSearch &&
            (status === 'all' || record.status === status);
    });

    membershipList.replaceChildren();

    if (filtered.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'text-center text-gray-500 py-6';
        empty.textContent = 'No membership requests found.';
        membershipList.appendChild(empty);
        return;
    }

    filtered.forEach(record => {
        const card = document.createElement('div');
        card.className = 'border rounded-xl p-5';

        const student = document.createElement('h3');
        student.className = 'text-lg font-bold text-blue-950';
        student.textContent = record.student?.name || 'Unknown Student';

        const club = document.createElement('p');
        club.className = 'text-gray-600 mt-2';
        club.textContent = 'Club: ' + record.club_name;

        const badge = document.createElement('span');
        badge.className = 'inline-block mt-3 px-3 py-1 rounded-full text-sm font-semibold';

        const colors = {
            pending: 'bg-yellow-100 text-yellow-700',
            approved: 'bg-green-100 text-green-700',
            rejected: 'bg-red-100 text-red-700'
        };

        badge.className += ' ' + (colors[record.status] || 'bg-gray-100 text-gray-700');
        badge.textContent = record.status;

        const actions = document.createElement('div');
        actions.className = 'flex flex-wrap gap-2 mt-4';

        if (record.status === 'pending') {
            const approve = document.createElement('button');
            approve.textContent = 'Approve';
            approve.className = 'bg-green-700 text-white px-4 py-2 rounded-lg';

            approve.addEventListener('click', () =>
                updateMembership(record.id, 'approve')
            );

            const reject = document.createElement('button');
            reject.textContent = 'Reject';
            reject.className = 'bg-red-700 text-white px-4 py-2 rounded-lg';

            reject.addEventListener('click', () =>
                updateMembership(record.id, 'reject')
            );

            actions.append(approve, reject);
        }

        card.append(student, club, badge, actions);
        membershipList.appendChild(card);
    });
}

async function updateMembership(id, action) {
    try {
        const result = await membershipApi(
            `/api/memberships/${id}/${action}`,
            { method: 'PATCH' }
        );

        membershipNotice(result.message, true);
        await loadMemberships();

    } catch (error) {
        membershipNotice(error.message);
    }
}

document.getElementById('searchMembership')
    .addEventListener('input', renderMemberships);

document.getElementById('membershipFilter')
    .addEventListener('change', renderMemberships);

loadMemberships();
</script>

@endsection
