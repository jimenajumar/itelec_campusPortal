<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\ClubMembership;
use Illuminate\Http\Request;

class ClubMembershipController extends Controller
{
    // Display membership requests
    public function index(Request $request, Club $club)
    {
        $user = $request->user();

        if (
            !in_array($user->role, ['admin', 'staff']) &&
            (int) $club->advisor_id !== (int) $user->id
        ) {
            return response()->json([
                'message' => 'You are not authorized to view these membership requests.',
            ], 403);
        }

        return response()->json(
            $club->memberships()->with('student')->latest()->get()
        );
    }

    // Student requests to join a club
    public function store(Request $request, Club $club)
    {
        if ($request->user()->role !== 'student') {
            return response()->json([
                'message' => 'Only students can request club membership.',
            ], 403);
        }

        if ($club->status !== 'approved') {
            return response()->json([
                'message' => 'You can only join approved clubs.',
            ], 403);
        }

        $existing = ClubMembership::where('club_id', $club->id)
            ->where('student_id', $request->user()->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'You already have a membership request for this club.',
            ], 409);
        }

        $membership = ClubMembership::create([
            'club_id' => $club->id,
            'student_id' => $request->user()->id,
            'status' => 'pending',
        ]);
            $this->logActivity(
            'requested',
            'club_memberships',
            'Requested to join club: ' . $club->name
    );

        return response()->json([
            'message' => 'Membership request submitted successfully.',
            'data' => $membership,
        ], 201);
    }

    // Approve a membership request
    public function approve(Request $request, ClubMembership $membership)
    {
        $user = $request->user();

        if (
            $user->role !== 'admin' &&
            (int) $membership->club->advisor_id !== (int) $user->id
        ) {
            return response()->json([
                'message' => 'You are not authorized to approve this request.',
            ], 403);
        }

        if ($membership->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending requests can be approved.',
            ], 422);
        }

        $membership->update(['status' => 'approved']);

        $this->logActivity(
            'approved',
            'club_memberships',
            'Approved membership for club: ' . $membership->club->name
        );

        return response()->json([
            'message' => 'Membership approved successfully.',
            'data' => $membership,
        ]);
    }

    // Reject a membership request
    public function reject(Request $request, ClubMembership $membership)
    {
        $user = $request->user();

        if (
            $user->role !== 'admin' &&
            (int) $membership->club->advisor_id !== (int) $user->id
        ) {
            return response()->json([
                'message' => 'You are not authorized to reject this request.',
            ], 403);
        }

        if ($membership->status !== 'pending') {
            return response()->json([
                'message' => 'Only pending requests can be rejected.',
            ], 422);
        }

            $membership->update(['status' => 'rejected']);

            $this->logActivity(
                'rejected',
                'club_memberships',
                'Rejected membership for club: ' . $membership->club->name
            );

            return response()->json([
                'message' => 'Membership rejected successfully.',
                'data' => $membership,
            ]);
    }
    // Display the logged-in student's membership status
public function myStatus(Request $request, Club $club)
{
    $membership = ClubMembership::where('club_id', $club->id)
        ->where('student_id', $request->user()->id)
        ->first();

    return response()->json([
        'status' => $membership?->status ?? 'not_requested',
    ]);
}
}