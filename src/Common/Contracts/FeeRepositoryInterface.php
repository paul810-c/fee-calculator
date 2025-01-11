<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Contracts;

interface FeeRepositoryInterface
{
    public function getFees(int $term): array;
}
