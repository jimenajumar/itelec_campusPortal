<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET: Display all rooms
    public function index()
    {
        $rooms = Room::latest()->get();

        return response()->json([
            'message' => 'Rooms retrieved successfully.',
            'data' => $rooms
        ], 200);
    }

    // POST: Create a new room
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'room_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:0',
            'room_type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $room = Room::create($validated);

        $this->logActivity(
        'created',
        'rooms',
        'Created room: ' . $room->room_number
    );

        return response()->json([
            'message' => 'Room created successfully.',
            'data' => $room
        ], 201);
    }

    // GET: Display one room
    public function show(Room $room)
    {
        return response()->json([
            'message' => 'Room retrieved successfully.',
            'data' => $room
        ], 200);
    }

    // PUT: Update a room
    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'sometimes|required|string|max:255|unique:rooms,room_number,' . $room->id,
            'room_name' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'capacity' => 'sometimes|required|integer|min:0',
            'room_type' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $room->update($validated);
        $this->logActivity(
        'updated',
        'rooms',
        'Updated room: ' . $room->room_number
    );

        return response()->json([
            'message' => 'Room updated successfully.',
            'data' => $room->fresh()
        ], 200);
    }

    // DELETE: Remove a room
    
public function destroy(Room $room)
{
    $roomNumber = $room->room_number;

    $room->delete();

    $this->logActivity(
        'deleted',
        'rooms',
        'Deleted room: ' . $roomNumber
    );

    return response()->json([
        'message' => 'Room deleted successfully.'
    ]);
}

}