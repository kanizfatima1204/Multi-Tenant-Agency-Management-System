<?php
namespace App\Policies;
use App\Models\Project; use App\Models\User;
class ProjectPolicy { public function viewAny(User $user):bool{return true;} public function view(User $user,Project $project):bool{return $user->isAdmin() || $project->tenant_id===$user->tenant_id;} public function create(User $user):bool{return $user->isAdmin()||$user->isTeam();} public function update(User $user,Project $project):bool{return $user->isAdmin()||($user->isTeam()&&$project->tenant_id===$user->tenant_id);} public function delete(User $user,Project $project):bool{return $user->isAdmin()||($user->isTeam()&&$project->tenant_id===$user->tenant_id);} }
