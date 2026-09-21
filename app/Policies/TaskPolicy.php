<?php
namespace App\Policies;
use App\Models\Task; use App\Models\User;
class TaskPolicy { public function view(User $user,Task $task):bool{return $user->isAdmin()||$task->tenant_id===$user->tenant_id;} public function create(User $user):bool{return $user->isAdmin()||$user->isTeam();} public function update(User $user,Task $task):bool{return $user->isAdmin()||($user->isTeam()&&$task->tenant_id===$user->tenant_id);} public function delete(User $user,Task $task):bool{return $user->isAdmin()||($user->isTeam()&&$task->tenant_id===$user->tenant_id);} }
