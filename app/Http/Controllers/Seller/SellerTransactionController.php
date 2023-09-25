<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view,seller')->only('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Seller $seller)
    {
        // seller -> product -> transaction
        $transactions = $seller->products()
        ->has('transactions')
        ->with('transactions')
        ->get()
        ->pluck('transactions')
        ->collapse();

        return $this->showTransactions($transactions);
    }
}
