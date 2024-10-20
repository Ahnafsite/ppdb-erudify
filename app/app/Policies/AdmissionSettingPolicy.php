<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AdmissionSetting;
use App\Models\User;

class AdmissionSettingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AdmissionSetting');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('view AdmissionSetting');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AdmissionSetting');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('update AdmissionSetting');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('delete AdmissionSetting');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any AdmissionSetting');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('restore AdmissionSetting');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any AdmissionSetting');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('replicate AdmissionSetting');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder AdmissionSetting');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdmissionSetting $admissionsetting): bool
    {
        return $user->checkPermissionTo('force-delete AdmissionSetting');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any AdmissionSetting');
    }
}
