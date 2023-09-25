<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('can:view,buyer')->only('show');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->allowedAdminAction();
        
        $buyers = Buyer::has('transactions')->get();

        return $this->showBuyers($buyers);
    }

    /**
     * Display the specified resource.
     */
    public function show(Buyer $buyer)
    {
        return $this->showBuyer($buyer);
    }
}
