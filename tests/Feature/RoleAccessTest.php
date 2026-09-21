<?php

namespace Tests\Feature;

use App\Models\{Project, Tenant, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_role_receives_its_dashboard(): void
    {
        $tenant = Tenant::create(['name' => 'Acme', 'slug' => 'acme']);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'password', 'role' => 'admin']);
        $client = User::create(['tenant_id' => $tenant->id, 'name' => 'Client', 'email' => 'client@example.test', 'password' => 'password', 'role' => 'client']);
        $team = User::create(['tenant_id' => $tenant->id, 'name' => 'Team', 'email' => 'team@example.test', 'password' => 'password', 'role' => 'team']);

        $this->actingAs($admin)->get('/')->assertInertia(fn ($page) => $page->component('Dashboard/Admin'));
        $this->actingAs($client)->get('/')->assertInertia(fn ($page) => $page->component('Dashboard/Client'));
        $this->actingAs($team)->get('/')->assertInertia(fn ($page) => $page->component('Dashboard/Team'));
    }

    public function test_api_projects_are_scoped_to_the_sanctum_user_tenant(): void
    {
        $acme = Tenant::create(['name' => 'Acme', 'slug' => 'acme']);
        $beta = Tenant::create(['name' => 'Beta', 'slug' => 'beta']);
        $client = User::create(['tenant_id' => $acme->id, 'name' => 'Client', 'email' => 'client@example.test', 'password' => 'password', 'role' => 'client']);
        Project::create(['tenant_id' => $acme->id, 'name' => 'Acme project', 'status' => 'active']);
        Project::create(['tenant_id' => $beta->id, 'name' => 'Beta project', 'status' => 'active']);

        Sanctum::actingAs($client);

        $this->getJson('/api/v1/projects')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Acme project');
    }

    public function test_client_cannot_create_a_project(): void
    {
        $tenant = Tenant::create(['name' => 'Acme', 'slug' => 'acme']);
        $client = User::create(['tenant_id' => $tenant->id, 'name' => 'Client', 'email' => 'client@example.test', 'password' => 'password', 'role' => 'client']);

        $this->actingAs($client)->post('/projects', [
            'name' => 'Unauthorized project', 'status' => 'active',
        ])->assertForbidden();
    }

    public function test_role_specific_workspace_pages_are_authorized(): void
    {
        $tenant = Tenant::create(['name' => 'Acme', 'slug' => 'acme']);
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@example.test', 'password' => 'password', 'role' => 'admin']);
        $client = User::create(['tenant_id' => $tenant->id, 'name' => 'Client', 'email' => 'client@example.test', 'password' => 'password', 'role' => 'client']);
        $team = User::create(['tenant_id' => $tenant->id, 'name' => 'Team', 'email' => 'team@example.test', 'password' => 'password', 'role' => 'team']);

        $this->actingAs($admin)->get('/clients')->assertInertia(fn ($page) => $page->component('Tenants/Index'));
        $this->actingAs($team)->get('/tasks')->assertInertia(fn ($page) => $page->component('Tasks/Index'));
        $this->actingAs($client)->get('/updates')->assertInertia(fn ($page) => $page->component('Updates/Index'));
        $this->actingAs($client)->get('/clients')->assertForbidden();
    }
}
