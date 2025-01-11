<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\BoundFinderInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\FeeCalculatorInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\InterpolationInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\RoundingServiceInterface;
use FinCalc\LoanFeeCalculator\Common\Utils\MoneyUtils;
use FinCalc\LoanFeeCalculator\Loan\Domain\LoanApplication;

abstract class BaseFeeCalculator implements FeeCalculatorInterface
{
    public function __construct(
        private readonly BoundFinderInterface $boundFinder,
        private readonly InterpolationInterface $interpolationStrategy,
        private readonly RoundingServiceInterface $roundingService
    ) {}

    public function calculate(LoanApplication $application): int
    {
        // Convert amount to pounds since getFees returns fees in pounds
        $amountInPounds = MoneyUtils::toPounds($application->amountInCents);

        // Retrieve fees for the specified loan term
        $fees = $this->getFees($application->term);

        // Find bounds for interpolation
        $lowerBound = $this->boundFinder->findLowerBound($fees, $amountInPounds);
        $upperBound = $this->boundFinder->findUpperBound($fees, $amountInPounds);

        // Perform interpolation
        $fee = $this->interpolationStrategy->interpolate($fees, $lowerBound, $upperBound, $amountInPounds);
        // Convert the fee back to cents for internal consistency
        $feeInCents = MoneyUtils::toCents($fee);

        // Calculate the total and round up to the nearest multiple of 5 cents
        $totalInCents = $application->amountInCents + $feeInCents;

        // Apply rounding to the total amount and subtract the original amount to get the rounded fee
        return $this->roundingService->round($totalInCents) - $application->amountInCents;
    }

    abstract protected function getFees(int $term): array;
}
