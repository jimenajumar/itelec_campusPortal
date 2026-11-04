<?php

use Illuminate\Support\Facades\Route;

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
