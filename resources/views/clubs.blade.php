
@extends('layouts.app')

@section('title', 'Clubs - ACES')

@section('content')

<div class="max-w-6xl mx-auto px-6 py-10">

    <h1 class="text-3xl font-bold text-blue-950">Campus Clubs</h1>

    <p class="text-gray-500 mt-2">
        Explore campus organizations and find a club to join.
    </p>

    <div class="bg-white rounded-xl shadow-md p-6 mt-8">

        <h2 class="text-xl font-bold text-blue-950 mb-6">
            Available Clubs
        </h2>

        <div id="clubMessage" class="mb-4 text-sm"></div>

        <div id="studentClubList"
             class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <p class="text-gray-500">Loading clubs...</p>

        </div>

    </div>
</div>

<script>
const studentToken = sessionStorage.getItem('campus_token');

const studentClubList = document.getElementById('studentClubList');
const clubMessage = document.getElementById('clubMessage');

function showClubMessage(message, success = false) {
    clubMessage.textContent = message;
    clubMessage.className = success
        ? 'mb-4 text-sm text-green-700'
        : 'mb-4 text-sm text-red-700';
}

async function loadStudentClubs() {
    try {
        if (!studentToken) {
            throw new Error('Please log in again to view campus clubs.');
        }

        const response = await fetch('/api/clubs', {
            headers: {
                'Accept': 'application/json',
                'Authorization': `Bearer ${studentToken}`
            }
        });

        const clubs = await response.json();

        if (!response.ok) {
            throw new Error(clubs.message || 'Unable to load clubs.');
        }

        studentClubList.replaceChildren();

        if (!clubs.length) {
            studentClubList.textContent = 'No approved clubs are available yet.';
            return;
        }

        clubs.forEach(club => {
            const card = document.createElement('div');
            card.className = 'border rounded-xl p-5';

            const name = document.createElement('h3');
            name.className = 'text-lg font-bold text-blue-950';
            name.textContent = club.name;

            const description = document.createElement('p');
            description.className = 'text-gray-500 mt-2';
            description.textContent =
                club.description || 'No description available.';
            
            const advisor = document.createElement('p');
            advisor.className = 'text-sm text-gray-600 mt-3';
                advisor.textContent = 'Club Advisor: ' +
            (club.advisor?.name || 'Not yet assigned');

            const membershipStatus = document.createElement('p');
membershipStatus.className = 'text-sm font-semibold mt-2';
membershipStatus.textContent = 'Membership Status: Checking...';

fetch(`/api/clubs/${club.id}/my-membership`, {
    headers: {
        'Accept': 'application/json',
        'Authorization': `Bearer ${studentToken}`
    }
})
.then(response => response.json())
.then(data => {
    const status = data.status || 'not_requested';

    const labels = {
        pending: 'Pending Approval',
        approved: 'Approved Member',
        rejected: 'Request Rejected',
        not_requested: 'Not Yet Requested'
    };

    membershipStatus.textContent =
        'Membership Status: ' + (labels[status] || status);

    membershipStatus.className =
        'text-sm font-semibold mt-2 ' +
        (status === 'approved' ? 'text-green-700' :
        status === 'pending' ? 'text-yellow-700' :
        status === 'rejected' ? 'text-red-700' :
        'text-gray-500');
})
.catch(() => {
    membershipStatus.textContent = 'Membership Status: Unavailable';
});

            const status = document.createElement('p');
            status.className = 'text-sm text-green-600 font-semibold mt-3';
            status.textContent = 'Membership open';

            const details = document.createElement('div');
            details.className = 'hidden mt-4 p-4 bg-gray-100 rounded-lg';

            const detailsTitle = document.createElement('h4');
            detailsTitle.className = 'font-bold text-blue-950';
            detailsTitle.textContent = 'Club Information';

            const detailsText = document.createElement('p');
            detailsText.className = 'text-gray-600 mt-2';
            detailsText.textContent =
                'Membership requests are subject to approval.';

            const requestButton = document.createElement('button');
            requestButton.className =
                'mt-3 bg-red-800 text-white px-4 py-2 rounded-lg';
            requestButton.textContent = 'Request Membership';

            requestButton.addEventListener('click', async () => {
                requestButton.disabled = true;

                try {
                    const request = await fetch(
                        `/api/clubs/${club.id}/memberships`,
                        {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${studentToken}`
                            }
                        }
                    );

                    const result = await request.json();

                    if (!request.ok) {
                        throw new Error(
                            result.message || 'Membership request failed.'
                        );
                    }

                    showClubMessage(result.message, true);
                    requestButton.textContent = 'Request Submitted';

                } catch (error) {
                    showClubMessage(error.message);
                    requestButton.disabled = false;
                }
            });

            const detailsButton = document.createElement('button');
            detailsButton.className =
                'mt-4 bg-red-800 text-white px-5 py-2 rounded-lg';
            detailsButton.textContent = 'View Details';

            detailsButton.addEventListener('click', () => {
                details.classList.toggle('hidden');
            });

            details.append(detailsTitle, detailsText, requestButton);

            card.append(
                name,
                description,
                advisor,
                membershipStatus,
                status,
                detailsButton,
                details
            );

            studentClubList.appendChild(card);
        });

    } catch (error) {
        studentClubList.replaceChildren();
        showClubMessage(error.message);
    }
}

loadStudentClubs();
</script>

@endsection
