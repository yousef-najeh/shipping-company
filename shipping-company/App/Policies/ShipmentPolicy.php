<?php

namespace App\Policies;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ShipmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user ,Shipment $shipment): bool
    {
        return $user->id === $shipment->user_id && ($user->role==='driver' || $user->client->vendors->order->shipment_id === $shipment->id);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Shipment $shipment): bool
    {
        return $user->id === $shipment->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role==='admin' || $user->role==='manager';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Shipment $shipment): bool
    {
        return $user->role==='admin' || $user->role==='manager';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Shipment $shipment): bool
    {
        return $user->role==='admin' || $user->role==='manager';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Shipment $shipment): bool
    {
        return $user->role==='admin' || $user->role==='manager';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Shipment $shipment): bool
    {
        return $user->role==='admin';
    }

    public function before(User $user, $ability)
    {
        if ($user->role === 'admin') {
            return true; 
        }
    }
}
