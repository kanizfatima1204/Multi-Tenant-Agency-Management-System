<?php

namespace Database\Seeders;

use App\Models\{Project, ProjectUpdate, Task, Tenant, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $acme = Tenant::firstOrCreate(['slug' => 'acme-digital'], ['name' => 'Acme Digital']);
        $beta = Tenant::firstOrCreate(['slug' => 'beta-studio'], ['name' => 'Beta Studio']);

        User::updateOrCreate(['email' => 'admin@sourcex.test'], ['name' => 'Platform Admin', 'password' => 'password', 'role' => 'admin', 'tenant_id' => null]);
        $acmeClient = User::updateOrCreate(['email' => 'client-a@sourcex.test'], ['name' => 'Acme Client', 'password' => 'password', 'role' => 'client', 'tenant_id' => $acme->id]);
        User::updateOrCreate(['email' => 'team@sourcex.test'], ['name' => 'Agency Team', 'password' => 'password', 'role' => 'team', 'tenant_id' => $acme->id]);
        $betaClient = User::updateOrCreate(['email' => 'client-b@sourcex.test'], ['name' => 'Beta Client', 'password' => 'password', 'role' => 'client', 'tenant_id' => $beta->id]);

        $acmeProject = Project::firstOrCreate(['tenant_id' => $acme->id, 'name' => 'Acme Website'], ['description' => 'Corporate website redesign', 'status' => 'active', 'due_date' => now()->addDays(21)]);
        Task::firstOrCreate(['project_id' => $acmeProject->id, 'title' => 'Homepage wireframe'], ['tenant_id' => $acme->id, 'status' => 'in_progress', 'priority' => 'high']);
        ProjectUpdate::firstOrCreate(['project_id' => $acmeProject->id, 'user_id' => $acmeClient->id, 'body' => 'Kickoff completed and requirements approved.'], ['tenant_id' => $acme->id]);

        $betaProject = Project::firstOrCreate(['tenant_id' => $beta->id, 'name' => 'Beta Commerce'], ['description' => 'E-commerce platform', 'status' => 'on_hold', 'due_date' => now()->addDays(45)]);
        Task::firstOrCreate(['project_id' => $betaProject->id, 'title' => 'Catalog import'], ['tenant_id' => $beta->id, 'status' => 'todo', 'priority' => 'medium']);
        ProjectUpdate::firstOrCreate(['project_id' => $betaProject->id, 'user_id' => $betaClient->id, 'body' => 'Waiting for product catalogue.'], ['tenant_id' => $beta->id]);
    }
}
