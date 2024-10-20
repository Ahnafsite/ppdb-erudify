<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentParent;
use App\Models\User;

class StudentParentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StudentParent');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('view StudentParent');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StudentParent');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('update StudentParent');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('delete StudentParent');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any StudentParent');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('restore StudentParent');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any StudentParent');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('replicate StudentParent');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder StudentParent');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentParent $studentparent): bool
    {
        return $user->checkPermissionTo('force-delete StudentParent');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any StudentParent');
    }
}
