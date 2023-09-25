<?php

namespace App\Providers;

use App\Mail\UserCreated;
use App\Models\Buyer;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Policies\BuyerPolicy;
use App\Policies\ProductPolicy;
use App\Policies\SellerPolicy;
use App\Policies\TransactionPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::created(function ($user) {
            retry(5, function () use ($user) {
                Log::debug('User::created - boot:' . json_encode($user));
                Log::debug('send email: ' . route('verify', $user->verification_token));
                // Mail::to($user->email)->send(new UserCreated($user));
            }, 100);
        });

        User::updated(function ($user) {
            retry(5, function () use ($user) {
                Log::debug('User::updated - boot:' . json_encode($user));
                if ($user->isDirty('email')) {
                    Log::debug('send email: ' . route('verify', $user->verification_token));
                    // Mail::to($user->email)->send(new UserCreated($user));
                }
            });
        });

        Product::updated(function ($product) {
            if ($product->quantity == 0 && $product->isAvailable()) {
                $product->status = Product::UNAVAILABLE_PRODUCT;

                $product->save();
            }
        });
    }
}
