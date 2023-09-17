<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
        $this->middleware('auth:api')->except(['index']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        // product -> category_product -> category
        $categories = $product->categories;

        return $this->showCategories($categories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product, Category $category)
    {
        // attach, sync, syncWithoutDetaching
        $product->categories()->syncWithoutDetaching($category->id);

        return $this->showCategories($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('The specified category is not a category of this product', 404);
        }
        
        // remove the relationship between categories and products, not the categories or products themselves
        $product->categories()->detach($category->id);

        return $this->showCategories($product->categories);
    }
}
