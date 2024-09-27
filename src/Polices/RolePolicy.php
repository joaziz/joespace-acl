<?php

namespace Joespace\ACL\Polices;

use App\Models\User;
use Joespace\ACL\Models\Role;

class RolePolicy
{
    public function update(User $user, Role $role): bool
    {
        if ($role->name === "super_admin")
            return false;

        return $user->hasPermissionTo("edit_role");
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo("list_roles");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->hasPermissionTo("view_role");

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo("create_role");

    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if ($role->name === "super_admin")
            return false;

        return $user->hasPermissionTo("delete_role");

    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->hasPermissionTo("create_role");

    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        if ($role->name === "super_admin")
            return false;

        return $user->hasPermissionTo("delete_role");
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasPermissionTo("delete_role");
    }
}
