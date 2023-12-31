<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $this->allowedAdminAction();
        
        // category -> category_product -> product -> transaction
        $transactions = $category->products()
        ->has('transactions')
        ->with('transactions')
        ->get()
        ->pluck('transactions')
        ->collapse();

        return $this->showTransactions($transactions);
    }
}
