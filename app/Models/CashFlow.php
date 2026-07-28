<?php

namespace App\Models;

use App\Enums\CashFlowType;
use App\Enums\MovementType;
use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CashFlow extends Model
{
    protected $table = 'cash_flow';

    protected $fillable = [
        'cash_register_id',
        'user_id',
        'type',
        'payment_method',
        'movement_type',
        'value',
        'description',
        'source_type',
        'source_id',
    ];

    protected $casts = [
        'value'=>'integer',
        'type' => CashFlowType::class,
        'payment_method' => PaymentMethod::class,
        'movement_type' => MovementType::class,
        'source_id'=>'integer',
    ];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
