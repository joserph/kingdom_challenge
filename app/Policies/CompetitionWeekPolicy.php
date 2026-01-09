<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CompetitionWeek;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompetitionWeekPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CompetitionWeek');
    }

    public function view(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('View:CompetitionWeek');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CompetitionWeek');
    }

    public function update(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('Update:CompetitionWeek');
    }

    public function delete(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('Delete:CompetitionWeek');
    }

    public function restore(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('Restore:CompetitionWeek');
    }

    public function forceDelete(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('ForceDelete:CompetitionWeek');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CompetitionWeek');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CompetitionWeek');
    }

    public function replicate(AuthUser $authUser, CompetitionWeek $competitionWeek): bool
    {
        return $authUser->can('Replicate:CompetitionWeek');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CompetitionWeek');
    }

}