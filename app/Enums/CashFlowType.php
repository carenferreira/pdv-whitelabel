<?php

namespace App\Enums;

enum CashFlowType: string
{
    case Entry = 'entry';
    case Withdrawal = 'withdrawal';

    public function label(): string
    {
        return match ($this) {
            self::Entry => 'Entrada',
            self::Withdrawal => 'Saída',
        };
    }
}