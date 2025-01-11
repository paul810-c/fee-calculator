<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

interface InterpolationInterface
{
    public function interpolate(array $fees, float $lower, float $upper, float $amount): float;
}