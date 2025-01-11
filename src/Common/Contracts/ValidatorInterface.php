<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

interface ValidatorInterface
{
    public function validate(int $term,  int $amount): void;
}
