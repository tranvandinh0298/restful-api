<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Traits\AdminActions;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    use AdminActions;
    /**
     * Determine whether the user can view the product.
     */
    public function addCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function deleteCategory(User $user, Product $product)
    {
        return $user->id === $product->seller->id;
    }
}
