
@extends('layouts.admin')

@section('title', 'Clubs Management')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Clubs Management</h2>
    <p class="text-gray-500">Manage campus clubs and approval requests.</p>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <div class="mb-6 border rounded-xl p-5">
    <h3 class="text-lg font-bold text-blue-950 mb-4">Add New Club</h3>

    <form id="addClubForm" class="space-y-3">
        <input
            type="text"
            id="clubName"
            placeholder="Club Name"
            class="w-full border rounded-lg px-4 py-2"
            required
        >

        <textarea
            id="clubDescription"
            placeholder="Club Description"
            class="w-full border rounded-lg px-4 py-2"
            rows="3"
        ></textarea>

        <button
            type="submit"
            class="bg-blue-950 text-white px-5 py-2 rounded-lg"
        >
            Add Club
        </button>
    </form>
</div>

    <div class="flex flex-col md:flex-row gap-3 mb-6">
        <input
            type="text"
            id="searchClub"
            placeholder="Search clubs..."
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

    <div id="clubList" class="space-y-4">
        <p class="text-gray-500">Loading clubs...</p>
    </div>

</div>

<script>
const token = sessionStorage.getItem('campus_token');
const clubList = document.getElementById('clubList');
const message = document.getElementById('message');

let clubs = [];
let advisors = [];

function showMessage(text, success = false) {
    message.textContent = text;
    message.className = success
        ? 'mb-4 text-sm text-green-700'
        : 'mb-4 text-sm text-red-700';
}

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
        throw new Error(result.message || 'Something went wrong.');
    }

    return result;
}

async function loadClubs() {
    try {
        clubs = await apiRequest('/api/clubs');
        console.log('Token:', token ? 'Present' : 'Missing');

        console.log('Loaded clubs:', clubs);
        console.log('Club count:', clubs.length);
        
        renderClubs();
    } catch (error) {
        showMessage(error.message);
        clubList.replaceChildren();
    }
}

function renderClubs() {
    const search = document.getElementById('searchClub')
        .value.toLowerCase();

    const status = document.getElementById('statusFilter').value;

    const filtered = clubs.filter(club => {
        const matchesSearch =
            (club.name || '').toLowerCase().includes(search) ||
            (club.description || '').toLowerCase().includes(search);

        return matchesSearch &&
            (status === 'all' || club.status === status);
    });

    clubList.replaceChildren();

    if (filtered.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'text-center text-gray-500 py-6';
        empty.textContent = 'No clubs found.';
        clubList.appendChild(empty);
        return;
    }

    filtered.forEach(club => {
        const card = document.createElement('div');
        card.className = 'border rounded-xl p-5';

        const name = document.createElement('h3');
        name.className = 'text-lg font-bold text-blue-950';
        name.textContent = club.name;

        const description = document.createElement('p');
        description.className = 'text-gray-600 my-3';
        description.textContent = club.description || 'No description provided.';

        const statusBadge = document.createElement('span');
        statusBadge.className = 'inline-block px-3 py-1 rounded-full text-sm font-semibold';

        const colors = {
            pending: 'bg-yellow-100 text-yellow-700',
            approved: 'bg-green-100 text-green-700',
            rejected: 'bg-red-100 text-red-700'
        };

        const currentStatus = club.status || 'pending';
        statusBadge.className += ' ' + (colors[currentStatus] || 'bg-gray-100 text-gray-700');
        statusBadge.textContent = currentStatus;
        
        const advisorSection = document.createElement('div');
advisorSection.className = 'mt-4 flex flex-wrap gap-2';

const advisorSelect = document.createElement('select');
advisorSelect.className = 'border rounded-lg px-3 py-2 flex-1';

const unassigned = document.createElement('option');
unassigned.value = '';
unassigned.textContent = 'No advisor assigned';
advisorSelect.appendChild(unassigned);

advisors.forEach(advisor => {
    const option = document.createElement('option');
    option.value = advisor.id;
    option.textContent = advisor.name;
    option.selected = Number(club.advisor_id) === Number(advisor.id);
    advisorSelect.appendChild(option);
});

const assignButton = document.createElement('button');
assignButton.textContent = 'Assign Advisor';
assignButton.className = 'bg-indigo-700 text-white px-4 py-2 rounded-lg';

assignButton.addEventListener('click', async () => {
    try {
        const result = await apiRequest(
            `/api/clubs/${club.id}/assign-advisor`,
            {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    advisor_id: advisorSelect.value || null
                })
            }
        );

        showMessage(result.message, true);
        await loadClubs();
    } catch (error) {
        showMessage(error.message);
    }
});

advisorSection.append(advisorSelect, assignButton);

        const actions = document.createElement('div');
        actions.className = 'flex flex-wrap gap-2 mt-4';

        const edit = document.createElement('button');
        edit.textContent = 'Edit';
        edit.className = 'bg-blue-700 text-white px-4 py-2 rounded-lg';
        edit.addEventListener('click', () => editClub(club));

        const remove = document.createElement('button');
        remove.textContent = 'Delete';
        remove.className = 'bg-red-700 text-white px-4 py-2 rounded-lg';
        remove.addEventListener('click', () => deleteClub(club));

        actions.append(edit);

        if (currentStatus === 'pending') {
            const approve = document.createElement('button');
            approve.textContent = 'Approve';
            approve.className = 'bg-green-700 text-white px-4 py-2 rounded-lg';
            approve.addEventListener('click', () => updateClubStatus(club.id, 'approve'));

            const reject = document.createElement('button');
            reject.textContent = 'Reject';
            reject.className = 'bg-orange-600 text-white px-4 py-2 rounded-lg';
            reject.addEventListener('click', () => updateClubStatus(club.id, 'reject'));

            actions.append(approve, reject);
        }

        actions.append(remove);
        card.append(name, description, statusBadge, advisorSection, actions);
        clubList.appendChild(card);
    });
}

async function updateClubStatus(id, action) {
    try {
        const result = await apiRequest(`/api/clubs/${id}/${action}`, {
            method: 'PATCH'
        });

        showMessage(result.message, true);
        await loadClubs();

    } catch (error) {
        showMessage(error.message);
    }
}

async function deleteClub(club) {
    if (!confirm(`Delete "${club.name}"?`)) return;

    try {
        const result = await apiRequest(`/api/clubs/${club.id}`, {
            method: 'DELETE'
        });

        showMessage(result.message, true);
        await loadClubs();

    } catch (error) {
        showMessage(error.message);
    }
}

async function editClub(club) {
    const name = prompt('Enter club name:', club.name);
    if (name === null) return;

    const description = prompt('Enter club description:', club.description || '');
    if (description === null) return;

    try {
        const result = await apiRequest(`/api/clubs/${club.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ name, description })
        });

        showMessage(result.message, true);
        await loadClubs();

    } catch (error) {
        showMessage(error.message);
    }
}

document.getElementById('searchClub')
    .addEventListener('input', renderClubs);

document.getElementById('statusFilter')
    .addEventListener('change', renderClubs);


async function loadAdvisors() {
    try {
        advisors = await apiRequest('/api/club-advisors');

        console.log('Loaded advisors:', advisors);
        console.log('Advisor count:', advisors.length);

    } catch (error) {
        console.error('Advisor loading error:', error);
        showMessage(error.message);
    }
}
document.getElementById('addClubForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const name = document.getElementById('clubName').value.trim();
    const description = document.getElementById('clubDescription').value.trim();

    try {
        const result = await apiRequest('/api/clubs', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                description: description
            })
        });

        showMessage(result.message, true);

        this.reset();

        await loadClubs();

    } catch (error) {
        showMessage(error.message);
    }
});
loadAdvisors().then(() => loadClubs());
</script>

@endsection
