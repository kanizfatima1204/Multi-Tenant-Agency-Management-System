<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectFileController;
use App\Http\Controllers\ProjectUpdateController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1')->name('login.store');
});

Route::middleware(['auth', 'tenant'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/clients', [WorkspaceController::class, 'tenants'])->name('clients.index');
    Route::get('/tasks', [WorkspaceController::class, 'tasks'])->name('tasks.index');
    Route::get('/updates', [WorkspaceController::class, 'updates'])->name('updates.index');

    Route::resource('projects', ProjectController::class)->only(['index', 'create', 'store', 'show', 'update', 'destroy']);
    Route::post('/projects/{project}/tasks', [TaskController::class, 'store'])->name('projects.tasks.store');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('/projects/{project}/updates', [ProjectUpdateController::class, 'store'])->name('projects.updates.store');
    Route::post('/projects/{project}/files', [ProjectFileController::class, 'store'])->name('projects.files.store');
    Route::get('/project-files/{projectFile}/download', [ProjectFileController::class, 'download'])->name('project-files.download');
    Route::delete('/project-files/{projectFile}', [ProjectFileController::class, 'destroy'])->name('project-files.destroy');
});
