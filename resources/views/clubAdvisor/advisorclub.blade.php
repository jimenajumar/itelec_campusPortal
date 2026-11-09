@extends('layouts.advisor')

@section('title', 'Clubs - ACES')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-blue-950">Manage the Campus clubs assigned to you</h1>

    <p class="text-gray-500 mt-2">
        Explore campus organizations and find a club to join.
    </p>

    <div class="bg-white rounded-xl shadow-md p-6 mt-8">

        <h2 class="text-xl font-bold text-blue-950 mb-6">
            My assigned clubs
        </h2>

        <div id="clubMessage" class="mb-4 text-sm"></div>

        <div id="studentClubList"
             class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <p class="text-gray-500">Loading clubs...</p>

        </div>

    </div>
</div>
<script>
const token = sessionStorage.getItem('campus_token');
const clubList = document.getElementById('studentClubList');
const message = document.getElementById('clubMessage');

async function loadClubs() {
    try {
        const response = await fetch('/api/clubs', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });

        const clubs = await response.json();

        if (!response.ok) {
            throw new Error(clubs.message || 'Unable to load clubs.');
        }

        clubList.replaceChildren();

        if (!clubs.length) {
            clubList.textContent = 'No assigned clubs found.';
            return;
        }

        clubs.forEach(club => {
            const card = document.createElement('div');
            card.className = 'border rounded-xl p-5';

            const name = document.createElement('h3');
            name.className = 'text-lg font-bold text-blue-950';
            name.textContent = club.name;

            const description = document.createElement('p');
            description.className = 'text-gray-600 mt-2';
            description.textContent = club.description || 'No description available.';

            const status = document.createElement('p');
            status.className = 'text-sm font-semibold mt-3';
            status.textContent = 'Status: ' + club.status;

            const editButton = document.createElement('button');
            editButton.className = 'mt-4 bg-red-800 text-white px-4 py-2 rounded-lg';
            editButton.textContent = 'Edit Club';

            editButton.addEventListener('click', () => {
                editClub(club);
            });

            card.append(name, description, status, editButton);
            clubList.appendChild(card);
        });

    } catch (error) {
        message.textContent = error.message;
        message.className = 'text-red-700 mb-4';
    }
}

async function editClub(club) {
    const name = prompt('Enter club name:', club.name);
    if (name === null) return;

    const description = prompt('Enter club description:', club.description || '');
    if (description === null) return;

    try {
        const response = await fetch(`/api/clubs/${club.id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({ name, description })
        });

        const result = await response.json();

        if (!response.ok) {
            throw new Error(result.message || 'Unable to update club.');
        }

        message.textContent = 'Club updated successfully.';
        message.className = 'text-green-700 mb-4';

        loadClubs();

    } catch (error) {
        message.textContent = error.message;
        message.className = 'text-red-700 mb-4';
    }
}

loadClubs();
</script>

@endsection
