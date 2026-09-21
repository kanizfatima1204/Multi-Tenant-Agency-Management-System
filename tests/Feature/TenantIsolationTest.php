<?php
namespace Tests\Feature;
use App\Models\{Tenant,User,Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TenantIsolationTest extends TestCase { use RefreshDatabase;
 public function test_client_only_sees_own_tenant_projects():void{$a=Tenant::create(['name'=>'A','slug'=>'a']);$b=Tenant::create(['name'=>'B','slug'=>'b']);$u=User::create(['tenant_id'=>$a->id,'name'=>'A Client','email'=>'a@test.local','password'=>'password','role'=>'client']);Project::create(['tenant_id'=>$a->id,'name'=>'A Project','status'=>'active']);Project::create(['tenant_id'=>$b->id,'name'=>'B Project','status'=>'active']);$this->actingAs($u)->get('/projects')->assertOk()->assertInertia(fn($page)=>$page->has('projects',1)->where('projects.0.name','A Project'));}
 public function test_cross_tenant_project_is_not_found():void{$a=Tenant::create(['name'=>'A','slug'=>'a']);$b=Tenant::create(['name'=>'B','slug'=>'b']);$u=User::create(['tenant_id'=>$a->id,'name'=>'A Client','email'=>'a2@test.local','password'=>'password','role'=>'client']);$p=Project::create(['tenant_id'=>$b->id,'name'=>'B Project','status'=>'active']);$this->actingAs($u)->get('/projects/'.$p->id)->assertNotFound();}
}
