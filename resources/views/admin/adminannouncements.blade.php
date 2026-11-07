
@extends('layouts.admin')

@section('title', 'Announcements Management')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-blue-950">Announcements Management</h2>
    <p class="text-gray-500">Create and manage campus announcements.</p>
</div>

<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h3 class="text-lg font-bold mb-4" id="formTitle">Create Announcement</h3>

    <form id="announcementForm" class="space-y-4">
        <input type="hidden" id="announcementId">

        <input
            type="text"
            id="title"
            placeholder="Announcement title"
            required
            class="w-full border rounded-lg p-3"
        >

        <input
            type="text"
            id="category"
            placeholder="Enter category"
            required
            class="w-full border rounded-lg p-3"
        >

        <textarea
            id="content"
            placeholder="Announcement content"
            required
            rows="4"
            class="w-full border rounded-lg p-3"
        ></textarea>

        <label class="flex items-center gap-2">
            <input type="checkbox" id="published">
            Publish announcement
        </label>

        <div class="flex gap-2">
            <button
                type="submit"
                class="bg-red-800 text-white px-5 py-2 rounded-lg hover:bg-red-900"
            >
                Save Announcement
            </button>

            <button
                type="button"
                id="cancelEdit"
                class="hidden bg-gray-200 px-5 py-2 rounded-lg"
            >
                Cancel
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex flex-col md:flex-row gap-3 mb-5">
        <input
            type="text"
            id="searchAnnouncement"
            placeholder="Search announcements..."
            class="border rounded-lg px-4 py-2 flex-1"
        >

        <select id="statusFilter" class="border rounded-lg px-4 py-2">
            <option value="all">All Statuses</option>
            <option value="published">Published</option>
            <option value="unpublished">Unpublished</option>
        </select>
    </div>

    <div id="message" class="mb-4 text-sm"></div>

    <div id="announcementList" class="space-y-4">
        <p class="text-gray-500">Loading announcements...</p>
    </div>

</div>

<script>
const token = sessionStorage.getItem('campus_token');

const form = document.getElementById('announcementForm');
const list = document.getElementById('announcementList');
const message = document.getElementById('message');

let announcements = [];

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
            'Content-Type': 'application/json',
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

async function loadAnnouncements() {
    try {
        announcements = await apiRequest('/api/announcements');
        renderAnnouncements();
    } catch (error) {
        showMessage(error.message);
        list.replaceChildren();
    }
}

function renderAnnouncements() {
    const search = document.getElementById('searchAnnouncement')
        .value.toLowerCase();

    const status = document.getElementById('statusFilter').value;

    const filtered = announcements.filter(item => {
        const matchesSearch =
            (item.title || '').toLowerCase().includes(search) ||
            (item.content || '').toLowerCase().includes(search);

        const matchesStatus =
            status === 'all' ||
            (status === 'published' && item.is_published) ||
            (status === 'unpublished' && !item.is_published);

        return matchesSearch && matchesStatus;
    });

    list.replaceChildren();

    if (filtered.length === 0) {
        const empty = document.createElement('p');
        empty.className = 'text-center text-gray-500 py-6';
        empty.textContent = 'No announcements found.';
        list.appendChild(empty);
        return;
    }

    filtered.forEach(item => {
        const card = document.createElement('div');
        card.className = 'border rounded-xl p-5';

        const heading = document.createElement('h3');
        heading.className = 'font-bold text-lg text-blue-950';
        heading.textContent = item.title;

        const category = document.createElement('p');
        category.className = 'text-sm text-gray-500 mt-1';
        category.textContent = item.category || 'General';

        const content = document.createElement('p');
        content.className = 'my-3 text-gray-700 whitespace-pre-wrap';
        content.textContent = item.content;

        const statusText = document.createElement('span');
        statusText.className = item.is_published
            ? 'text-green-700 font-semibold text-sm'
            : 'text-yellow-700 font-semibold text-sm';

        statusText.textContent = item.is_published
            ? 'Published'
            : 'Unpublished';

        const actions = document.createElement('div');
        actions.className = 'flex flex-wrap gap-2 mt-4';

        const edit = document.createElement('button');
        edit.textContent = 'Edit';
        edit.className = 'bg-blue-700 text-white px-4 py-2 rounded-lg';
        edit.addEventListener('click', () => editAnnouncement(item));

        const toggle = document.createElement('button');
        toggle.textContent = item.is_published ? 'Unpublish' : 'Publish';
        toggle.className = 'bg-green-700 text-white px-4 py-2 rounded-lg';
        toggle.addEventListener('click', () => toggleAnnouncement(item));

        const remove = document.createElement('button');
        remove.textContent = 'Delete';
        remove.className = 'bg-red-700 text-white px-4 py-2 rounded-lg';
        remove.addEventListener('click', () => deleteAnnouncement(item));

        actions.append(edit, toggle, remove);
        card.append(heading, category, content, statusText, actions);
        list.appendChild(card);
    });
}

form.addEventListener('submit', async function(event) {
    event.preventDefault();

    const id = document.getElementById('announcementId').value;

    const data = {
        title: document.getElementById('title').value,
        category: document.getElementById('category').value,
        content: document.getElementById('content').value,
        is_published: document.getElementById('published').checked
    };

    try {
        const result = await apiRequest(
            id ? `/api/announcements/${id}` : '/api/announcements',
            {
                method: id ? 'PUT' : 'POST',
                body: JSON.stringify(data)
            }
        );

        showMessage(result.message, true);
        resetForm();
        await loadAnnouncements();

    } catch (error) {
        showMessage(error.message);
    }
});

function editAnnouncement(item) {
    document.getElementById('announcementId').value = item.id;
    document.getElementById('title').value = item.title || '';
    document.getElementById('category').value = item.category || '';
    document.getElementById('content').value = item.content || '';
    document.getElementById('published').checked = Boolean(item.is_published);

    document.getElementById('formTitle').textContent = 'Edit Announcement';
    document.getElementById('cancelEdit').classList.remove('hidden');

    form.scrollIntoView({ behavior: 'smooth' });
}

async function toggleAnnouncement(item) {
    try {
        const result = await apiRequest(`/api/announcements/${item.id}`, {
            method: 'PUT',
            body: JSON.stringify({
                is_published: !item.is_published
            })
        });

        showMessage(result.message, true);
        await loadAnnouncements();

    } catch (error) {
        showMessage(error.message);
    }
}

async function deleteAnnouncement(item) {
    if (!confirm(`Delete "${item.title}"?`)) return;

    try {
        const result = await apiRequest(`/api/announcements/${item.id}`, {
            method: 'DELETE'
        });

        showMessage(result.message, true);
        await loadAnnouncements();

    } catch (error) {
        showMessage(error.message);
    }
}

function resetForm() {
    form.reset();
    document.getElementById('announcementId').value = '';
    document.getElementById('formTitle').textContent = 'Create Announcement';
    document.getElementById('cancelEdit').classList.add('hidden');
}

document.getElementById('cancelEdit').addEventListener('click', resetForm);

document.getElementById('searchAnnouncement')
    .addEventListener('input', renderAnnouncements);

document.getElementById('statusFilter')
    .addEventListener('change', renderAnnouncements);

loadAnnouncements();
</script>

@endsection
