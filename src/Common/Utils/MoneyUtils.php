<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Utils;

class MoneyUtils
{
    public static function toCents(int|string|float $amountInPounds): int
    {
        return (int) $amountInPounds * 100;
    }

    public static function toPounds(int $amountInCents): float
    {
        return $amountInCents / 100;
    }
}
