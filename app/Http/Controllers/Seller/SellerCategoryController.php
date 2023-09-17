<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        // seller -> product -> category_product -> category
        $categories = $seller->products()
            ->has('categories')
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            ->unique()
            ->values();

        return $this->showCategories($categories);
    }
}
