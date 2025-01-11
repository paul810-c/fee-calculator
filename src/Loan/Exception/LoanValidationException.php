<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Exception;

use RuntimeException;

class LoanValidationException extends RuntimeException
{
    public function __construct(string $validationError)
    {
        parent::__construct($validationError);
    }
}
