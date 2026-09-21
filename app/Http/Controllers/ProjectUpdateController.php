<?php
namespace App\Http\Controllers;
use App\Models\Project; use App\Models\ProjectUpdate; use Illuminate\Http\Request;
class ProjectUpdateController extends Controller { public function store(Request $r,Project $project){$this->authorize('view',$project);$d=$r->validate(['body'=>'required|string|max:5000']);ProjectUpdate::create(['tenant_id'=>$project->tenant_id,'project_id'=>$project->id,'user_id'=>$r->user()->id,'body'=>$d['body']]);return back()->with('success','Update posted.');} }
