<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentDocument;
use App\Models\User;

class StudentDocumentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StudentDocument');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('view StudentDocument');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StudentDocument');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('update StudentDocument');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('delete StudentDocument');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any StudentDocument');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('restore StudentDocument');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any StudentDocument');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('replicate StudentDocument');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder StudentDocument');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentDocument $studentdocument): bool
    {
        return $user->checkPermissionTo('force-delete StudentDocument');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any StudentDocument');
    }
}
