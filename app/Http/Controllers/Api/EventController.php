<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Display events
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Event::latest();

        // Admin can see all events.
        // Club Advisors and Students can only see approved events.
        if ($user->role !== 'admin') {
        $query->where('status', 'approved');
        }

        return response()->json($query->get());
    }

    // Create an event
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'nullable|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['status'] = 'pending';

        $event = Event::create($validated);
        $this->logActivity(
    'created',
    'events',
    'Created event: ' . $event->title
    
);

        return response()->json([
            'message' => 'Event created successfully. Awaiting approval.',
            'data' => $event,
        ], 201);
    }

    // Display one event
    public function show(Request $request, Event $event)
    {
        $user = $request->user();

        if (
            $user->role !== 'admin' &&
        $event->status !== 'approved'
        ) {
            return response()->json([
                'message' => 'Event not found.',
            ], 404);
        }

        return response()->json($event);
    }

    // Update an event
    public function update(Request $request, Event $event)
    {
        $user = $request->user();

       if ($user->role !== 'admin') {
    return response()->json([
        'message' => 'Only administrators can update events.',
    ], 403);
}

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
            'venue' => 'sometimes|nullable|string|max:255',
            'starts_at' => 'sometimes|required|date',
            'ends_at' => 'sometimes|required|date|after:starts_at',
        ]);

        $event->update($validated);
        $this->logActivity(
        'updated',
        'events',
        'Updated event: ' . $event->title
    );
        return response()->json([
            'message' => 'Event updated successfully.',
            'data' => $event,
        ]);
    }

   // Delete an event
    public function destroy(Request $request, Event $event)
    {
        $user = $request->user();

        if ($user->role !== 'admin') {
            return response()->json([
            'message' => 'Only administrators can delete events.',
            ], 403);
    }

        $this->logActivity(
            'deleted',
            'events',
            'Deleted event: ' . $event->title
        );

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully.',
        ]);
    }

    // Approve an event
    public function approve(Request $request, Event $event)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can approve events.',
        ], 403);
    }

    if ($event->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending events can be approved.',
        ], 422);
    }

    $event->update(['status' => 'approved']);

        $this->logActivity(
            'approved',
            'events',
            'Approved event: ' . $event->title
        );

        return response()->json([
            'message' => 'Event approved successfully.',
            'data' => $event,
        ]);
    }

    // Reject an event
    public function reject(Request $request, Event $event)
{
    if ($request->user()->role !== 'admin') {
        return response()->json([
            'message' => 'Only administrators can reject events.',
        ], 403);
    }

    if ($event->status !== 'pending') {
        return response()->json([
            'message' => 'Only pending events can be rejected.',
        ], 422);
    }

    $event->update(['status' => 'rejected']);

        $this->logActivity(
            'rejected',
            'events',
            'Rejected event: ' . $event->title
        );

        return response()->json([
            'message' => 'Event rejected successfully.',
            'data' => $event,
        ]);
    }
}
