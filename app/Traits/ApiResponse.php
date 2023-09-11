<?php

namespace App\Traits;

use App\Http\Resources\BuyerResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SellerResource;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Buyer;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

trait ApiResponse
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(JsonResource $collection, $code = 200)
    {
        Log::debug(gettype($collection));
        Log::debug(get_class($collection));
        Log::debug(is_array($collection));
        Log::debug(json_encode($collection));

        // Log::debug('get_class: ' . get_class($collection));

        // Log::debug(json_encode($collection));
        $collection = $this->filterData($collection);

        // Log::debug('get_class: ' . get_class($collection));

        $collection = $this->sortData($collection);

        // Log::debug('get_class: ' . get_class($collection));



        return $this->successResponse(['data' => $collection], $code);
    }

    protected function showOne(JsonResource $instance, $code = 200)
    {
        Log::debug(gettype($instance));
        Log::debug(json_encode($instance));
        return $this->successResponse(['data' => $instance], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function showUser(Model $user, $code = 200)
    {
        return $this->showOne(new UserResource($user), $code);
    }

    protected function showUsers(Collection $users, $code = 200)
    {
        return $this->showAll(UserResource::collection($users), $code);
    }

    protected function showBuyer(Buyer $buyer, $code = 200)
    {
        return $this->showOne(new BuyerResource($buyer), $code);
    }

    protected function showBuyers(Collection $buyers, $code = 200)
    {
        return $this->showAll(BuyerResource::collection($buyers), $code);
    }

    protected function showSeller(Seller $seller, $code = 200)
    {
        return $this->showOne(new SellerResource($seller), $code);
    }

    protected function showSellers(Collection $sellers, $code = 200)
    {
        return $this->showAll(SellerResource::collection($sellers), $code);
    }

    protected function showCategory(Category $category, $code = 200)
    {
        return $this->showOne(new CategoryResource($category), $code);
    }

    protected function showCategories(Collection $categories, $code = 200)
    {
        return $this->showAll(CategoryResource::collection($categories), $code);
    }

    protected function showProduct(Product $product, $code = 200)
    {
        return $this->showOne(new ProductResource($product), $code);
    }

    protected function showProducts(Collection $products, $code = 200)
    {
        return $this->showAll(ProductResource::collection($products), $code);
    }

    protected function showTransaction(Transaction $transaction, $code = 200)
    {
        return $this->showOne(new TransactionResource($transaction), $code);
    }

    protected function showTransactions(Collection $transactions, $code = 200)
    {
        return $this->showAll(TransactionResource::collection($transactions), $code);
    }

    protected function sortData(JsonResource $collection)
    {
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by;

            $collection = $collection->sortBy($attribute);
        }
        return JsonResource::collection($collection);
    }

    protected function filterData(JsonResource $collection)
    {
        foreach (request()->query() as $query => $value) {
            $collection = $collection->filter(function ($instance) use ($query, $value) {
                $instance = collect($instance);
                return $instance->has($query) && $instance[$query] == $value;
            })->values();
        }
        return JsonResource::collection($collection);
    }
}
