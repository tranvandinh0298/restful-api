<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Transaction $transaction)
    {
        // lấy ra người bán sản phẩm nằm trong giao dịch
        $seller = $transaction->product->seller;

        return $this->showOne($seller);
    }
}
