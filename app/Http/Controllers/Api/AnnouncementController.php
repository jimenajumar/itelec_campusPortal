<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
   public function index(Request $request)
{
    $user = $request->user();

    $query = Announcement::latest();

    // Students and Club Advisors can only see published announcements.
    if (!in_array($user->role, ['admin', 'staff'])) {
        $query->where('is_published', true);
    }

    return response()->json($query->get());
}

    public function store(Request $request)
{
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'category' => 'nullable|string|max:255',
        'is_published' => 'sometimes|boolean',
        'published_at' => 'nullable|date',
    ]);

    $validated['created_by'] = $request->user()->id;

    $announcement = Announcement::create($validated);
    $this->logActivity(
    'created',
    'announcements',
    'Created announcement: ' . $announcement->title
);

    return response()->json([
        'message' => 'Announcement created successfully.',
        'data' => $announcement,
    ], 201);
}

    public function show(Request $request, Announcement $announcement)
{
    $user = $request->user();

    if (
        !in_array($user->role, ['admin', 'staff']) &&
        !$announcement->is_published
    ) {
        return response()->json([
            'message' => 'Announcement not found.',
        ], 404);
    }

    return response()->json($announcement);
}
    public function update(Request $request, Announcement $announcement)
{
    $validated = $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'content' => 'sometimes|required|string',
        'category' => 'sometimes|nullable|string|max:255',
        'is_published' => 'sometimes|boolean',
        'published_at' => 'sometimes|nullable|date',
    ]);

    $announcement->update($validated);
    $this->logActivity(
    'updated',
    'announcements',
    'Updated announcement: ' . $announcement->title
);

    return response()->json([
        'message' => 'Announcement updated successfully.',
        'data' => $announcement,
    ]);
}


public function destroy(Announcement $announcement)
{
    $title = $announcement->title;

    $announcement->delete();

    $this->logActivity(
        'deleted',
        'announcements',
        'Deleted announcement: ' . $title
    );

    return response()->json([
        'message' => 'Announcement deleted successfully.'
    ]);
}

}
