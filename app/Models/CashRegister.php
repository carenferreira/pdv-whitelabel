<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    protected $fillable = [
        'user_id',
        'opening_value',
        'opening_date',
        'closing_date',
        'expected_value',
        'actual_value',
        'difference',
        'status',
        'observations',
    ];

    protected $casts = [
        'opening_value'=>'integer',
        'opening_date'=>'datetime',
        'closing_date'=>'datetime',
        'expected_value'=>'integer',
        'actual_value'=>'integer',
        'difference'=>'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(CashFlow::class, 'cash_register_id');
    }
}
