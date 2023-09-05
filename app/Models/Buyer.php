<?php

namespace App\Models;

use App\Models\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Buyer extends User
{
    use HasFactory;
    protected $table = 'users';

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // parent boot
        parent::boot();
        
        // applying global scopes
        static::addGlobalScope(new BuyerScope);
    }

    public function transactions(): HasMany {
        return $this->hasMany(Transaction::class);
    }
}
