<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Common\Enums;

enum LoanAmountRange: int
{
    case MIN = 1000;
    case MAX = 20000;
}
