<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\BoundFinderInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\FeeRepositoryInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\InterpolationInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\RoundingServiceInterface;

class FeeCalculator extends BaseFeeCalculator
{
    public function __construct(
        private readonly FeeRepositoryInterface $repository,
        BoundFinderInterface $boundFinder,
        InterpolationInterface $interpolationStrategy,
        RoundingServiceInterface $roundingService
    ) {
        parent::__construct($boundFinder, $interpolationStrategy, $roundingService);
    }

    protected function getFees(int $term): array
    {
        return $this->repository->getFees($term);
    }
}
