<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Domain;

final class LoanApplication
{
    public function __construct(
        public readonly int $term,
        public readonly int $amountInCents
    ) {
    }
}
