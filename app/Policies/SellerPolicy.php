<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class SellerPolicy
{
    use AdminActions;
    /**
     * Determine whether the user can view the seller.
     */
    public function view(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can sale product.
     */
    public function sale(User $user, User $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can update a product.
     */
    public function editProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }

    /**
     * Determine whether the user can delete a product.
     */
    public function deleteProduct(User $user, Seller $seller)
    {
        return $user->id === $seller->id;
    }
}
