<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    protected $hidden = [
        'pivot'
    ];

    public function isAvailable() {
        return $this->status == Product::AVAILABLE_PRODUCT;
    }

    public function categories(): BelongsToMany {
        return $this->belongsToMany(Category::class);
    }

    public function seller(): BelongsTo { 
        return $this->belongsTo(Seller::class);
    }

    public function transactions(): BelongsToMany {
        return $this->belongsToMany(Transaction::class);
    }
}
