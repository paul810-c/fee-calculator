<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

interface RoundingServiceInterface
{
    public function round(int $amount): int;
}
