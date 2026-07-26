<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',   
        'name',
        'description',
        'price_in_cents',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price_in_cents' => 'integer',
    ];

     public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
