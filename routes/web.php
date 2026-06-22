<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Client\DashboardController as ClientDashboardController;
use App\Http\Controllers\Developer\DashboardController as DeveloperDashboardController;

Route::get('/', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

// Authentication Routes
Route::prefix('api/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
    });
});

// Admin Routes
Route::middleware(['auth:sanctum', 'admin'])->prefix('api/admin')->group(function () {
    Route::get('dashboard', [AdminDashboardController::class, 'index']);
    Route::get('projects', [AdminDashboardController::class, 'getAllProjects']);
    Route::get('users', [AdminDashboardController::class, 'getAllUsers']);
    Route::put('users/{user}/status', [AdminDashboardController::class, 'updateUserStatus']);
    Route::put('projects/{project}/status', [AdminDashboardController::class, 'updateProjectStatus']);
    Route::post('projects/{project}/assign-developer', [AdminDashboardController::class, 'assignProjectToDeveloper']);
});

// Client Routes
Route::middleware(['auth:sanctum', 'client'])->prefix('api/client')->group(function () {
    Route::get('dashboard', [ClientDashboardController::class, 'index']);
    Route::post('projects', [ClientDashboardController::class, 'createProject']);
    Route::get('projects/{project}', [ClientDashboardController::class, 'getProject']);
    Route::put('projects/{project}', [ClientDashboardController::class, 'updateProject']);
    Route::get('projects/{project}/developer/{developerId}/progress', [ClientDashboardController::class, 'getDeveloperProgress']);
});

// Developer Routes
Route::middleware(['auth:sanctum', 'developer'])->prefix('api/developer')->group(function () {
    Route::get('dashboard', [DeveloperDashboardController::class, 'index']);
    Route::get('assignments', [DeveloperDashboardController::class, 'getAssignments']);
    Route::post('assignments/{assignment}/accept', [DeveloperDashboardController::class, 'acceptAssignment']);
    Route::post('assignments/{assignment}/reject', [DeveloperDashboardController::class, 'rejectAssignment']);
    Route::get('tasks', [DeveloperDashboardController::class, 'getTasks']);
    Route::put('tasks/{task}', [DeveloperDashboardController::class, 'updateTask']);
    Route::get('tasks/{task}/progress', [DeveloperDashboardController::class, 'getTaskProgress']);
    Route::put('tasks/{task}/progress', [DeveloperDashboardController::class, 'updateTaskProgress']);
    Route::get('tasks/status/{status}', [DeveloperDashboardController::class, 'getTasksByStatus']);
});


