<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Service;

use FinCalc\LoanFeeCalculator\Common\Contracts\BoundFinderInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\FeeRepositoryInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\InterpolationInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\RoundingServiceInterface;
use FinCalc\LoanFeeCalculator\Loan\Domain\LoanApplication;
use FinCalc\LoanFeeCalculator\Loan\Service\FeeCalculator;
use PHPUnit\Framework\TestCase;

class FeeCalculatorTest extends TestCase
{
    private FeeCalculator $feeCalculator;
    private FeeRepositoryInterface $repository;
    private BoundFinderInterface $boundFinder;
    private InterpolationInterface $interpolation;
    private RoundingServiceInterface $roundingService;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(FeeRepositoryInterface::class);
        $this->boundFinder = $this->createMock(BoundFinderInterface::class);
        $this->interpolation = $this->createMock(InterpolationInterface::class);
        $this->roundingService = $this->createMock(RoundingServiceInterface::class);

        $this->feeCalculator = new FeeCalculator(
            $this->repository,
            $this->boundFinder,
            $this->interpolation,
            $this->roundingService
        );
    }

    public function testCalculateFeeWithExactBreakpoint(): void
    {
        $this->repository
            ->method('getFees')
            ->with(12)
            ->willReturn([
                1000 => 50,
                2000 => 90,
            ]);

        $this->boundFinder
            ->method('findLowerBound')
            ->willReturn(1000.0);

        $this->boundFinder
            ->method('findUpperBound')
            ->willReturn(1000.0);

        $this->interpolation
            ->method('interpolate')
            ->willReturn(50.0);

        $this->roundingService
            ->method('round')
            ->willReturn(1050);

        $loanApplication = new LoanApplication(12, 1000);

        $fee = $this->feeCalculator->calculate($loanApplication);

        $this->assertSame(50, $fee);
    }

    public function testCalculateFeeWithAmountBetweenBreakpoints(): void
    {
        $this->repository
            ->method('getFees')
            ->with(12)
            ->willReturn([
                1000 => 50,
                2000 => 90,
            ]);

        $this->boundFinder
            ->method('findLowerBound')
            ->willReturn(1000.0);

        $this->boundFinder
            ->method('findUpperBound')
            ->willReturn(2000.0);

        $this->interpolation
            ->method('interpolate')
            ->willReturn(70.0);

        $this->roundingService
            ->method('round')
            ->willReturn(1570);

        $loanApplication = new LoanApplication(12, 1500);

        $fee = $this->feeCalculator->calculate($loanApplication);

        $this->assertSame(70, $fee);
    }
}
