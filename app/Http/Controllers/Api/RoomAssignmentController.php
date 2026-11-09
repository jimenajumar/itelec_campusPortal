<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RoomAssignment;
use Illuminate\Http\Request;

class RoomAssignmentController extends Controller
{
    // GET: Display all room assignments
    public function index()
    {
        $assignments = RoomAssignment::with(['room', 'assignedBy'])
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Room assignments retrieved successfully.',
            'data' => $assignments
        ], 200);
    }

    // POST: Create a room assignment
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'You are not authorized to create room assignments.'
            ], 403);
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'assignment_type' => 'required|in:class,event,meeting,other',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        // Check whether the room is already booked during this time.
        $overlap = RoomAssignment::where('room_id', $validated['room_id'])
            ->where('status', 'scheduled')
            ->where('starts_at', '<', $validated['ends_at'])
            ->where('ends_at', '>', $validated['starts_at'])
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'This room is already booked during the selected time.'
            ], 409);
        }

        $validated['assigned_by'] = $request->user()->id;
        $validated['status'] = 'scheduled';

        $assignment = RoomAssignment::create($validated);
        $this->logActivity(
        'created',
        'room_assignments',
        'Created room assignment: ' . $assignment->title
    );

        return response()->json([
            'message' => 'Room assignment created successfully.',
            'data' => $assignment->load(['room', 'assignedBy'])
        ], 201);
    }

    // GET: Display one room assignment
    public function show(RoomAssignment $roomAssignment)
    {
        return response()->json([
            'message' => 'Room assignment retrieved successfully.',
            'data' => $roomAssignment->load(['room', 'assignedBy'])
        ], 200);
    }

    // PUT: Update a room assignment
    public function update(Request $request, RoomAssignment $roomAssignment)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'You are not authorized to update room assignments.'
            ], 403);
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'title' => 'required|string|max:255',
            'assignment_type' => 'required|in:class,event,meeting,other',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        // Check for overlapping bookings, excluding this assignment.
        $overlap = RoomAssignment::where('room_id', $validated['room_id'])
            ->where('status', 'scheduled')
            ->where('id', '!=', $roomAssignment->id)
            ->where('starts_at', '<', $validated['ends_at'])
            ->where('ends_at', '>', $validated['starts_at'])
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'This room is already booked during the selected time.'
            ], 409);
        }

        $roomAssignment->update($validated);
        $this->logActivity(
        'updated',
        'room_assignments',
        'Updated room assignment: ' . $roomAssignment->title
    );

        return response()->json([
            'message' => 'Room assignment updated successfully.',
            'data' => $roomAssignment->fresh()->load(['room', 'assignedBy'])
        ], 200);
    }

    // DELETE: Remove a room assignment
    public function destroy(RoomAssignment $roomAssignment)
    {
    if (request()->user()->role !== 'admin') {
        return response()->json([
            'message' => 'You are not authorized to delete room assignments.'
        ], 403);
    }

    // Save the title before deleting
    $title = $roomAssignment->title;

    // Delete the assignment
    $roomAssignment->delete();

    // Record the activity
    $this->logActivity(
        'deleted',
        'room_assignments',
        'Deleted room assignment: ' . $title
    );

    return response()->json([
        'message' => 'Room assignment deleted successfully.',
    ]);
}
}
