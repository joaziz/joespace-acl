<?php

namespace Joespace\ACL\Polices;

use App\Models\User;

class UserPolicy
{


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("list_users");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $project): bool
    {
        return $user->hasPermissionTo("view_user");

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create_user");

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $project): bool
    {
        return $user->hasPermissionTo("edit_user");

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $record): bool
    {
        if ($record->id === $user->id)
            return false;
        return $user->hasPermissionTo("delete_user");

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $project): bool
    {
        return $user->hasPermissionTo("create_user");

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $record): bool
    {
        if ($record->id === $user->id)
            return false;
        return $user->hasPermissionTo("delete_user");
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo("delete_user");
    }
}
