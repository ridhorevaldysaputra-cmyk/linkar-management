<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Attendance;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('view_any_attendance');
    }

    public function view(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('view_attendance');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('create_attendance');
    }

    public function update(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('update_attendance');
    }

    public function delete(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('delete_attendance');
    }

    public function restore(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('restore_attendance');
    }

    public function forceDelete(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('force_delete_attendance');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('force_delete_any_attendance');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('restore_any_attendance');
    }

    public function replicate(AuthUser $authUser, Attendance $attendance): bool
    {
        return $authUser->can('replicate_attendance');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('reorder_attendance');
    }

}