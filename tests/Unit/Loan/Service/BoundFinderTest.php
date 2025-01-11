<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Service;

use FinCalc\LoanFeeCalculator\Loan\Service\BoundFinder;
use PHPUnit\Framework\TestCase;

class BoundFinderTest extends TestCase
{
    private BoundFinder $boundFinder;

    protected function setUp(): void
    {
        $this->boundFinder = new BoundFinder();
    }

    public function testFindLowerBoundReturnsCorrectValue(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
            3000 => 120,
        ];

        $amount = 2500;

        $lowerBound = $this->boundFinder->findLowerBound($fees, $amount);

        $this->assertSame(2000.0, $lowerBound);
    }

    public function testFindLowerBoundThrowsExceptionWhenNoLowerBoundExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No lower bound found for the given amount.');

        $fees = [
            2000 => 90,
            3000 => 120,
        ];

        $amount = 1000;

        $this->boundFinder->findLowerBound($fees, $amount);
    }

    public function testFindUpperBoundReturnsCorrectValue(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
            3000 => 120,
        ];

        $amount = 1500;

        $upperBound = $this->boundFinder->findUpperBound($fees, $amount);

        $this->assertSame(2000.0, $upperBound);
    }

    public function testFindUpperBoundThrowsExceptionWhenNoUpperBoundExists(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No upper bound found for the given amount.');

        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $amount = 3000;

        $this->boundFinder->findUpperBound($fees, $amount);
    }

    public function testFindLowerBoundWithExactMatch(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $amount = 1000;

        $lowerBound = $this->boundFinder->findLowerBound($fees, $amount);

        $this->assertSame(1000.0, $lowerBound);
    }

    public function testFindUpperBoundWithExactMatch(): void
    {
        $fees = [
            1000 => 50,
            2000 => 90,
        ];

        $amount = 2000;

        $upperBound = $this->boundFinder->findUpperBound($fees, $amount);

        $this->assertSame(2000.0, $upperBound);
    }
}
