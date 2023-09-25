<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view,buyer')->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Buyer $buyer)
    {
        // buyer -> transaction -> product -> category_product -> category
        $categories =  $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse()
        ->unique('id')
        ->values();
        return $this->showCategories($categories);
    }
}
