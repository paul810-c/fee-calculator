<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\InterpolationInterface;

class LinearInterpolation implements InterpolationInterface
{
    public function interpolate(array $fees, float $lower, float $upper, float $amount): float
    {
        if ($lower === $upper) {
            return $fees[$lower];
        }

        $lowerFee = $fees[$lower];
        $upperFee = $fees[$upper];

        return $lowerFee + (($upperFee - $lowerFee) * ($amount - $lower)) / ($upper - $lower);
    }
}