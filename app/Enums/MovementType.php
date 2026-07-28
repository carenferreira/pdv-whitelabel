<?php

namespace App\Enums;

enum MovementType: string
{
    case Sale = 'sale';
    case Refund = 'refund';
    case Expense = 'expense';
    case Sangria = 'sangria';
    case Suprimento = 'suprimento';
    case Opening = 'opening';

    public function label(): string
    {
        return match ($this) {
            self::Sale => 'Venda',
            self::Refund => 'Estorno',
            self::Expense => 'Despesa',
            self::Sangria => 'Sangria',
            self::Suprimento => 'Suprimento',
            self::Opening => 'Abertura',
        };
    }
}