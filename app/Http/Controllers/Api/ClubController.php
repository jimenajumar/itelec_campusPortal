<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubMembership;
use App\Models\User;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    // Display clubs
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Club::with('advisor')->latest();

        // Admin and Staff can see all clubs.
        // Club Advisors can see their own clubs and approved clubs.
        // Students can only see approved clubs.
        if ($user->role === 'club_advisor') {
            $query->where(function ($q) use ($user) {
                $q->where('created_by', $user->id)
                    ->orWhere('status', 'approved');
            });
        } elseif (!in_array($user->role, ['admin', 'staff'])) {
            $query->where('status', 'approved');
        }

        return response()->json($query->get());
    }

    // Create a club
    public function store(Request $request)
    {
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
            'Created club: ' . $club->name
        );

        return response()->json([
            'message' => 'Club created successfully. Awaiting admin approval.',
            'data' => $club,
        ], 201);
    }

    // Display one club
    public function show(Request $request, Club $club)
    {
        $user = $request->user();

        if (
            !in_array($user->role, ['admin', 'staff']) &&
            $club->status !== 'approved' &&
            !($user->role === 'club_advisor' &&
              (int) $club->created_by === (int) $user->id)
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
            $user->role === 'club_advisor' &&
            (int) $club->advisor_id !== (int) $user->id
        ) {
            return response()->json([
                'message' => 'You can only update clubs assigned to you.',
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
            $user->role === 'club_advisor' &&
            (int) $club->advisor_id !== (int) $user->id
        ) {
            return response()->json([
                'message' => 'You can only delete clubs assigned to you.',
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
    public function approve(Club $club)
    {
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
    public function reject(Club $club)
    {
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
public function advisors()
{
    $advisors = User::where('role', 'club_advisor')
        ->orderBy('name')
        ->get(['id', 'name', 'email']);

    return response()->json($advisors);
}

// Assign or remove a club advisor
public function assignAdvisor(Request $request, Club $club)
{
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
