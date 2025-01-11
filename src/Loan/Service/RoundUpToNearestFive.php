<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\RoundingServiceInterface;

class RoundUpToNearestFive implements RoundingServiceInterface
{
    public function round(int $amount): int
    {
        return (int) ceil($amount / 5) * 5;
    }
}