<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\User;
use Illuminate\Http\Request;

class ClubController extends Controller
{
   // Display clubs
    public function index(Request $request)
    {
    $user = $request->user();

    $query = Club::with('advisor')->latest();

    // Admin can see all clubs.
    if ($user->role === 'admin') {
        // No additional filter.
    }
    // Club Advisors can see their assigned clubs and approved clubs.
    elseif ($user->role === 'club_advisor') {
        $query->where(function ($q) use ($user) {
            $q->where('advisor_id', $user->id)
              ->orWhere('status', 'approved');
        });
    }
    // Students can only see approved clubs.
    else {
        $query->where('status', 'approved');
    }

    return response()->json($query->get());
}

    // Create a club
public function store(Request $request)
{
    if ($request->user()->role !== 'club_advisor') {
        return response()->json([
            'message' => 'Only club advisors can submit club creation requests.'
        ], 403);
    }

    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:clubs,name',
        'description' => 'nullable|string',
    ]);

    $validated['created_by'] = $request->user()->id;
    $validated['status'] = 'pending';

    $club = Club::create($validated);

    $this->logActivity(
        'created',
        'clubs',
        'Submitted club creation request: ' . $club->name
    );

    return response()->json([
        'message' => 'Club creation request submitted successfully. Awaiting admin approval.',
        'data' => $club,
    ], 201);
}

    // Display one club
public function show(Request $request, Club $club)
{
    $user = $request->user();

    if (
        $user->role !== 'admin' &&
        $club->status !== 'approved' &&
        !(
            $user->role === 'club_advisor' &&
            (int) $club->created_by === (int) $user->id
        ) &&
        !(
            $user->role === 'club_advisor' &&
            (int) $club->advisor_id === (int) $user->id
        )
    ) {
        return response()->json([
            'message' => 'Club not found.',
        ], 404);
    }

    return response()->json($club->load('advisor'));
}

    // Update a club
public function update(Request $request, Club $club)
{
    $user = $request->user();

    if (
        $user->role !== 'admin' &&
        !(
            $user->role === 'club_advisor' &&
            (int) $club->advisor_id === (int) $user->id
        )
    ) {
        return response()->json([
            'message' => 'You are not authorized to update this club.',
        ], 403);
    }

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255|unique:clubs,name,' . $club->id,
        'description' => 'sometimes|nullable|string',
    ]);

    $club->update($validated);

    $this->logActivity(
        'updated',
        'clubs',
        'Updated club: ' . $club->name
    );

    return response()->json([
        'message' => 'Club updated successfully.',
        'data' => $club,
    ]);
}
    // Delete a club
public function destroy(Request $request, Club $club)
{
    $user = $request->user();

    if (
        $user->role !== 'admin' &&
        !(
            $user->role === 'club_advisor' &&
            (int) $club->advisor_id === (int) $user->id
        )
    ) {
        return response()->json([
            'message' => 'You are not authorized to delete this club.',
        ], 403);
    }

    $clubName = $club->name;

    $club->delete();

    $this->logActivity(
        'deleted',
        'clubs',
        'Deleted club: ' . $clubName
    );

    return response()->json([
        'message' => 'Club deleted successfully.',
    ]);
}

   // Approve a club
public function approve(Request $request, Club $club)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can approve club requests.',
        ], 403);
    }

    if ($club->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending club requests can be approved.',
        ], 422);
    }

    $club->update(['status' => 'approved']);

    $this->logActivity(
        'approved',
        'clubs',
        'Approved club: ' . $club->name
    );

    return response()->json([
        'message' => 'Club approved successfully.',
        'data' => $club,
    ]);
}

    // Reject a club
public function reject(Request $request, Club $club)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can reject club requests.',
        ], 403);
    }

    if ($club->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending club requests can be rejected.',
        ], 422);
    }

    $club->update(['status' => 'rejected']);

    $this->logActivity(
        'rejected',
        'clubs',
        'Rejected club: ' . $club->name
    );

    return response()->json([
        'message' => 'Club rejected successfully.',
        'data' => $club,
    ]);
}
    // List available club advisors
public function advisors(Request $request)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can view the advisor list.',
        ], 403);
    }

    $advisors = User::where('role', 'club_advisor')
        ->orderBy('name')
        ->get(['id', 'name', 'email']);

    return response()->json($advisors);
}

// Assign or remove a club advisor
public function assignAdvisor(Request $request, Club $club)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can assign club advisors.',
        ], 403);
    }

    $validated = $request->validate([
        'advisor_id' => 'nullable|integer|exists:users,id',
    ]);

    if (!empty($validated['advisor_id'])) {
        $advisor = User::findOrFail($validated['advisor_id']);

        if ($advisor->role !== 'club_advisor') {
            return response()->json([
                'message' => 'Selected user is not a club advisor.',
            ], 422);
        }
    }

    $club->update([
        'advisor_id' => $validated['advisor_id'] ?? null,
    ]);

    $this->logActivity(
        'updated',
        'clubs',
        'Assigned advisor to club: ' . $club->name
    );

    return response()->json([
        'message' => 'Club advisor updated successfully.',
        'data' => $club->load('advisor'),
    ]);
}
}
