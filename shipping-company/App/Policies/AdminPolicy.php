<?php

namespace App\Policies;

use App\Models\AdminProfile;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminPolicy
{
    /**
     * Determine whether the user can view any models.
     */
public function viewAny(User $user): bool
{
    if ($user->role !== 'admin') {
        return false;
    }
    
    return $user->admin && $user->admin->user_id === $user->id;
}

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AdminProfile $adminProfile): bool
    {
        return $user->id === $adminProfile->user_id 
                && $user->role==='admin';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role === "admin";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AdminProfile $adminProfile): bool
    {
        return $user->role === "admin" && $user->id === $adminProfile->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AdminProfile $adminProfile): bool
    {
        return $user->role === "admin" && $user->id === $adminProfile->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AdminProfile $adminProfile): bool
    {
        return $user->role === "admin";
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AdminProfile $adminProfile): bool
    {
        return $user->role === "admin";
    }
}
