<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

use FinCalc\LoanFeeCalculator\Loan\Domain\LoanApplication;

interface FeeCalculatorInterface
{
    public function calculate(LoanApplication $application): int;
}
