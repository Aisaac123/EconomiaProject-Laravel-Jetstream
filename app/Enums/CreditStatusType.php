<?php

namespace App\Enums;

enum CreditStatusType: string
{
    case CALCULATED = 'calculated';
    case CALCULATED_UPDATED = 'calculated-updated';
    case CALCULATED_COPIED = 'calculated-copied';
    case PENDING = 'pending';
    case PAID = 'paid';
    case REJECTED = 'rejected';

    public static function label(): array
    {
        return [
            self::CALCULATED->value => 'Calculado',
            self::PENDING->value => 'Pendiente',
            self::PAID->value => 'Pagado',
            self::REJECTED->value => 'Rechazado',
        ];
    }

    public static function fromValue(string $value): ?self
    {
        return match ($value) {
            self::CALCULATED->value => self::CALCULATED,
            self::PENDING->value => self::PENDING,
            self::PAID->value => self::PAID,
            self::REJECTED->value => self::REJECTED,
            default => null,
        };
    }

    public static function labelFor(string $value): ?string
    {
        return self::label()[$value] ?? null;
    }
}
