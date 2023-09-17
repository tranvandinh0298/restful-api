<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        // product => transaction -> buyer
        $buyers = $product->transactions()->with('buyer')
            ->get()
            ->pluck('buyer')
            ->unique()
            ->values();

        return $this->showBuyers($buyers);
    }
}
