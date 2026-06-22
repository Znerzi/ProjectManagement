<?php
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route for your login page
Route::get('/login', function () {
    return Inertia::render('client/components/login');
});

// Route for your forms page
Route::get('/forms', function () {
    return Inertia::render('client/components/forms');
});
