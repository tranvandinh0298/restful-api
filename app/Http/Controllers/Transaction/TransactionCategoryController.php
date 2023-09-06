<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction)
    {
        // lấy ra tất cả cá thể loại ứng với sản phẩm nằm trong giao dịch
        $categories = $transaction->product->categories;

        return $this->showAll($categories);
    }
}
