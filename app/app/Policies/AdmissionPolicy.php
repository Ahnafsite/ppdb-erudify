<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Admission;
use App\Models\User;

class AdmissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Admission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('view Admission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Admission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('update Admission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('delete Admission');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Admission');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('restore Admission');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Admission');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('replicate Admission');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Admission');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Admission $admission): bool
    {
        return $user->checkPermissionTo('force-delete Admission');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Admission');
    }
}
