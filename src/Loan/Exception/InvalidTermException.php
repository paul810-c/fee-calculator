<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Exception;

use RuntimeException;

final class InvalidTermException extends RuntimeException
{
    public function __construct(int $term)
    {
        parent::__construct(sprintf('Invalid term: %d', $term));
    }
}
