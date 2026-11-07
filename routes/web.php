<?php
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\AdminDashboardController;

Route::get('/', function () {
    return view('login');
});
Route::get('/dashboard', function() {
    return view('dashboard');
});
Route::get('/schedule', function() {
    return view('schedule');
});
Route::get('/events', function() {
    return view('events');
});
Route::get('/announcements', function() {
    return view('announcements');
});
Route::get('/weather', function() {
    return view('weather');
});
Route::get('/profile', function() {
    return view('profile');
});
Route::get('/login', function() {
    return view('login');
});
Route::get('/clubs', function() {
    return view('clubs');
});
Route::get('/rooms', function() {
    return view('rooms');
});
Route::get('/room-assignments', function() {
    return view('room-assignments');
});
Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index']);
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/admin/events', function () {
    return view('admin.adminevents');
});
Route::get('/admin/announcements', function () {
    return view('admin.adminannouncements');
});
Route::get('/admin/clubs', function () {
    return view('admin.adminclubs');
});
Route::get('/admin/rooms', function () {
    return view('admin.adminrooms');
});
Route::get('/admin/room-assignments', function () {
    return view('admin.adminroomasign');
});
Route::get('/admin/activity-logs', function () {
    return view('admin.adminactivitylogs');
});

});
Route::post('/web-login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Invalid email or password.',
        ], 401);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    // Keep the API token for your existing dashboard requests.
    $token = $user->createToken('campus-portal')->plainTextToken;

    return response()->json([
        'message' => 'Login successful.',
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'student_id' => $user->student_id,
        ],
        'token' => $token,
        'redirect' => $user->role === 'admin'
            ? '/admin/dashboard'
            : '/dashboard',
    ]);
});
Route::post('/web-logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->middleware('auth');
