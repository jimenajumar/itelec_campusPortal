
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\ClubController;
use App\Http\Controllers\Api\ClubMembershipController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomAssignmentController;
use App\Http\Controllers\Api\ActivityLogController;
use Illuminate\Http\Request;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // Announcements - viewing
    Route::get('/announcements', [AnnouncementController::class, 'index']);
    Route::get('/announcements/{announcement}', [AnnouncementController::class, 'show']);

    // Announcements - management
    Route::middleware('announcement.manager')->group(function () {
        Route::post('/announcements', [AnnouncementController::class, 'store']);
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update']);
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy']);
    });

    // Campus Events - viewing
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    // Campus Events - management
    Route::middleware('event.manager')->group(function () {
        Route::post('/events', [EventController::class, 'store']);
        Route::put('/events/{event}', [EventController::class, 'update']);
        Route::delete('/events/{event}', [EventController::class, 'destroy']);
    });

    // Admin-only event approval
    Route::middleware('admin')->group(function () {
        Route::patch('/events/{event}/approve', [EventController::class, 'approve']);
        Route::patch('/events/{event}/reject', [EventController::class, 'reject']);
    });
    // Clubs - viewing
    Route::get('/clubs', [ClubController::class, 'index']);
    Route::get('/clubs/{club}', [ClubController::class, 'show']);

    // Clubs - management
    Route::middleware('club.manager')->group(function () {
        Route::post('/clubs', [ClubController::class, 'store']);
        Route::put('/clubs/{club}', [ClubController::class, 'update']);
        Route::delete('/clubs/{club}', [ClubController::class, 'destroy']);
    });

    // Admin-only club approval
    Route::middleware('club.admin')->group(function () {
        Route::patch('/clubs/{club}/approve', [ClubController::class, 'approve']);
        Route::patch('/clubs/{club}/reject', [ClubController::class, 'reject']);
    });

    // Admin-only club advisor management
    Route::middleware('admin')->group(function () {
        Route::get('/club-advisors', [ClubController::class, 'advisors']);
        Route::patch('/clubs/{club}/assign-advisor', [ClubController::class, 'assignAdvisor']);
    });

    // Club Membership - view requests
    Route::get('/clubs/{club}/memberships', [
        ClubMembershipController::class,
        'index'
    ]);

    // Students - request to join a club
    Route::post('/clubs/{club}/memberships', [
        ClubMembershipController::class,
        'store'
    ]);

    // Approve or reject membership requests
    Route::patch('/memberships/{membership}/approve', [
        ClubMembershipController::class,
        'approve'
    ]);

    Route::patch('/memberships/{membership}/reject', [
        ClubMembershipController::class,
        'reject'
    ]);
    Route::get('/clubs/{club}/my-membership', [
    ClubMembershipController::class,
    'myStatus'
    ]);

    Route::get('/user', function (Request $request) {
    return $request->user();
    });
    // Rooms - Everyone authenticated can view
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);

    // Rooms - Everyone authenticated can view
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);

    // Rooms - Only Admin and Staff can manage
    Route::middleware('room.manager')->group(function () {
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::put('/rooms/{room}', [RoomController::class, 'update']);
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy']);
    });

    // Room Assignments
    Route::get('/room-assignments', [RoomAssignmentController::class, 'index']);
    Route::post('/room-assignments', [RoomAssignmentController::class, 'store']);
    Route::get('/room-assignments/{roomAssignment}', [RoomAssignmentController::class, 'show']);
    Route::put('/room-assignments/{roomAssignment}', [RoomAssignmentController::class, 'update']);
    Route::delete('/room-assignments/{roomAssignment}', [RoomAssignmentController::class, 'destroy']);

    // Activity Logs - Admin only
    Route::middleware('admin')->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    Route::get('/activity-logs/{activityLog}', [ActivityLogController::class, 'show']);
    });

});
