<?php

namespace App\Http\Controllers;

use App\Models\{ProjectUpdate, Task, Tenant};
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    public function tenants(): Response
    {
        abort_unless(request()->user()->isAdmin(), 403);

        return Inertia::render('Tenants/Index', [
            'tenants' => Tenant::withCount(['users', 'projects'])
                ->orderBy('name')
                ->get()
                ->map(fn (Tenant $tenant) => [
                    'id' => $tenant->id,
                    'name' => $tenant->name,
                    'slug' => $tenant->slug,
                    'status' => $tenant->status,
                    'users_count' => $tenant->users_count,
                    'projects_count' => $tenant->projects_count,
                ]),
        ]);
    }

    public function tasks(): Response
    {
        abort_unless(request()->user()->isTeam(), 403);

        return Inertia::render('Tasks/Index', [
            'tasks' => Task::with('project:id,name')
                ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")
                ->latest()
                ->get()
                ->map(fn (Task $task) => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'status' => $task->status,
                    'priority' => $task->priority,
                    'due_date' => $task->due_date?->format('Y-m-d'),
                    'project' => $task->project?->name,
                ]),
        ]);
    }

    public function updates(): Response
    {
        abort_unless(request()->user()->isClient(), 403);

        return Inertia::render('Updates/Index', [
            'updates' => ProjectUpdate::with(['project:id,name', 'user:id,name'])
                ->latest()
                ->get()
                ->map(fn (ProjectUpdate $update) => [
                    'id' => $update->id,
                    'body' => $update->body,
                    'project' => $update->project?->name,
                    'author' => $update->user?->name,
                    'created_at' => $update->created_at->diffForHumans(),
                ]),
        ]);
    }
}
