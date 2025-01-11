<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Service;

use FinCalc\LoanFeeCalculator\Loan\Service\LinearInterpolation;
use PHPUnit\Framework\TestCase;

class LinearInterpolationTest extends TestCase
{
    private LinearInterpolation $interpolation;

    protected function setUp(): void
    {
        $this->interpolation = new LinearInterpolation();
    }

    public function testInterpolateWithDifferentBounds(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $lower = 1000.0;
        $upper = 2000.0;
        $amount = 1500.0;

        $fee = $this->interpolation->interpolate($fees, $lower, $upper, $amount);

        $expectedFee = 70.0;
        $this->assertSame($expectedFee, $fee);
    }

    public function testInterpolateWithExactLowerBound(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $lower = 1000.0;
        $upper = 2000.0;
        $amount = 1000.0;

        $fee = $this->interpolation->interpolate($fees, $lower, $upper, $amount);

        $expectedFee = 50.0;
        $this->assertSame($expectedFee, $fee);
    }

    public function testInterpolateWithExactUpperBound(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $lower = 1000.0;
        $upper = 2000.0;
        $amount = 2000.0;

        $fee = $this->interpolation->interpolate($fees, $lower, $upper, $amount);

        $expectedFee = 90.0;
        $this->assertSame($expectedFee, $fee);
    }

    public function testInterpolateWithSameLowerAndUpperBounds(): void
    {
        $fees = [
            1000 => 50,
        ];

        $lower = 1000.0;
        $upper = 1000.0;
        $amount = 1000.0;

        $fee = $this->interpolation->interpolate($fees, $lower, $upper, $amount);

        $expectedFee = 50.0;
        $this->assertSame($expectedFee, $fee);
    }
}
