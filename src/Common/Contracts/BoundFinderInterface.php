<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

interface BoundFinderInterface
{
    public function findLowerBound(array $fees, float $amount): float;

    public function findUpperBound(array $fees, float $amount): float;
}