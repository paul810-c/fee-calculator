<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\BoundFinderInterface;

class BoundFinder implements BoundFinderInterface
{
    public function findLowerBound(array $fees, float $amount): float
    {
        $lowerBounds = [];
        foreach ($fees as $bound => $fee) {
            if ($bound <= $amount) {
                $lowerBounds[] = $bound;
            }
        }

        if (empty($lowerBounds)) {
            throw new \RuntimeException('No lower bound found for the given amount.');
        }

        return max($lowerBounds);
    }

    public function findUpperBound(array $fees, float $amount): float
    {
        $upperBounds = [];
        foreach ($fees as $bound => $fee) {
            if ($bound >= $amount) {
                $upperBounds[] = $bound;
            }
        }

        if (empty($upperBounds)) {
            throw new \RuntimeException('No upper bound found for the given amount.');
        }

        return min($upperBounds);
    }
}