<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    // Display all activity logs
    public function index()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Activity logs retrieved successfully.',
            'data' => $logs,
        ]);
    }

    // Display one activity log
    public function show(ActivityLog $activityLog)
    {
        $activityLog->load('user');

        return response()->json([
            'message' => 'Activity log retrieved successfully.',
            'data' => $activityLog,
        ]);
    }
}
