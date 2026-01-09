<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Youth;
use Illuminate\Auth\Access\HandlesAuthorization;

class YouthPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Youth');
    }

    public function view(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('View:Youth');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Youth');
    }

    public function update(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('Update:Youth');
    }

    public function delete(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('Delete:Youth');
    }

    public function restore(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('Restore:Youth');
    }

    public function forceDelete(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('ForceDelete:Youth');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Youth');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Youth');
    }

    public function replicate(AuthUser $authUser, Youth $youth): bool
    {
        return $authUser->can('Replicate:Youth');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Youth');
    }

}