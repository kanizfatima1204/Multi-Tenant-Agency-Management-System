<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\Tenant;
use App\Models\ProjectUpdate;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = request()->user();

        if ($user->isAdmin()) {
            $projects = Project::with('tenant')->withCount('tasks')->latest()->take(8)->get();
            $tenants = Tenant::withCount('users')->withCount('projects')->orderBy('name')->get();

            return Inertia::render('Dashboard/Admin', [
                'stats' => [
                    'tenants' => Tenant::count(),
                    'projects' => Project::count(),
                    'active_projects' => Project::where('status', 'active')->count(),
                    'open_tasks' => Task::whereIn('status', ['todo', 'in_progress'])->count(),
                ],
                'projects' => $projects->map(fn ($p) => [
                    'id' => $p->id, 'name' => $p->name, 'status' => $p->status,
                    'tenant' => $p->tenant?->name, 'tasks_count' => $p->tasks_count,
                    'due_date' => $p->due_date?->format('M d, Y'),
                ]),
                'tenants' => $tenants->map(fn ($t) => [
                    'id' => $t->id, 'name' => $t->name, 'slug' => $t->slug,
                    'users_count' => $t->users_count, 'projects_count' => $t->projects_count,
                ]),
            ]);
        }

        $projects = Project::withCount(['tasks', 'updates'])->latest()->get();
        $recentUpdates = ProjectUpdate::with(['project:id,name', 'user:id,name'])
            ->latest()->take(6)->get();

        if ($user->isClient()) {
            return Inertia::render('Dashboard/Client', [
                'stats' => [
                    'projects' => $projects->count(),
                    'active' => $projects->where('status', 'active')->count(),
                    'completed' => $projects->where('status', 'completed')->count(),
                    'tasks' => Task::whereIn('status', ['todo', 'in_progress'])->count(),
                ],
                'projects' => $projects->take(6)->map(fn ($p) => [
                    'id' => $p->id, 'name' => $p->name, 'description' => $p->description,
                    'status' => $p->status, 'due_date' => $p->due_date?->format('M d, Y'),
                    'tasks_count' => $p->tasks_count, 'updates_count' => $p->updates_count,
                ]),
                'updates' => $recentUpdates->map(fn ($u) => [
                    'id' => $u->id, 'project' => $u->project?->name, 'author' => $u->user?->name,
                    'body' => $u->body, 'created_at' => $u->created_at->diffForHumans(),
                ]),
            ]);
        }

        $tasks = Task::with('project:id,name')->whereIn('status', ['todo', 'in_progress'])->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 ELSE 3 END")->latest()->take(10)->get();

        return Inertia::render('Dashboard/Team', [
            'stats' => [
                'projects' => $projects->count(),
                'in_progress' => Task::where('status', 'in_progress')->count(),
                'todo' => Task::where('status', 'todo')->count(),
                'done' => Task::where('status', 'done')->count(),
            ],
            'projects' => $projects->take(5)->map(fn ($p) => [
                'id' => $p->id, 'name' => $p->name, 'status' => $p->status,
                'due_date' => $p->due_date?->format('M d, Y'), 'tasks_count' => $p->tasks_count,
            ]),
            'tasks' => $tasks->map(fn ($t) => [
                'id' => $t->id, 'title' => $t->title, 'status' => $t->status,
                'priority' => $t->priority, 'project' => $t->project?->name,
                'due_date' => $t->due_date?->format('M d, Y'),
            ]),
        ]);
    }
}
