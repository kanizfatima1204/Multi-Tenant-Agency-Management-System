<?php

namespace App\Policies;

use App\Models\ProjectFile;
use App\Models\User;

class ProjectFilePolicy
{
    public function view(User $user, ProjectFile $file): bool
    {
        return $user->isAdmin() || $file->tenant_id === $user->tenant_id;
    }

    public function delete(User $user, ProjectFile $file): bool
    {
        return $user->isAdmin() || ($user->isTeam() && $file->tenant_id === $user->tenant_id);
    }
}
