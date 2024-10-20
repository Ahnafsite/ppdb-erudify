<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DocumentRequirement;
use App\Models\User;

class DocumentRequirementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DocumentRequirement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('view DocumentRequirement');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DocumentRequirement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('update DocumentRequirement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('delete DocumentRequirement');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any DocumentRequirement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('restore DocumentRequirement');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any DocumentRequirement');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('replicate DocumentRequirement');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder DocumentRequirement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DocumentRequirement $documentrequirement): bool
    {
        return $user->checkPermissionTo('force-delete DocumentRequirement');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any DocumentRequirement');
    }
}
