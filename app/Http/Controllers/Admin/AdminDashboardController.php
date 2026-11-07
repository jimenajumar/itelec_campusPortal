<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Club;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalStudents = User::where('role', 'student')->count();

        $pendingEvents = Event::where('status', 'pending')->count();

        $totalAnnouncements = Announcement::count();

        $totalClubs = Club::count();

        return view('admin.adminboard', compact(
            'totalStudents',
            'pendingEvents',
            'totalAnnouncements',
            'totalClubs'
        ));
    }
}
