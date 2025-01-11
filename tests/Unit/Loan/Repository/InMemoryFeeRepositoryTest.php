<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Repository;

use FinCalc\LoanFeeCalculator\Loan\Repository\InMemoryFeeRepository;
use FinCalc\LoanFeeCalculator\Loan\Exception\InvalidTermException;
use FinCalc\LoanFeeCalculator\Common\Enums\LoanTerm;
use PHPUnit\Framework\TestCase;

class InMemoryFeeRepositoryTest extends TestCase
{
    private InMemoryFeeRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryFeeRepository();
    }

    private function getExpectedFeesForTerm(LoanTerm $term): array
    {
        return match ($term) {
            LoanTerm::TWELVE_MONTHS => [
                1000 => 50, 2000 => 90, 3000 => 90, 4000 => 115, 5000 => 100,
                6000 => 120, 7000 => 140, 8000 => 160, 9000 => 180, 10000 => 200,
                11000 => 220, 12000 => 240, 13000 => 260, 14000 => 280, 15000 => 300,
                16000 => 320, 17000 => 340, 18000 => 360, 19000 => 380, 20000 => 400,
            ],
            LoanTerm::TWENTY_FOUR_MONTHS => [
                1000 => 70, 2000 => 100, 3000 => 120, 4000 => 160, 5000 => 200,
                6000 => 240, 7000 => 280, 8000 => 320, 9000 => 360, 10000 => 400,
                11000 => 440, 12000 => 480, 13000 => 520, 14000 => 560, 15000 => 600,
                16000 => 640, 17000 => 680, 18000 => 720, 19000 => 760, 20000 => 800,
            ],
        };
    }

    public function testGetFeesForTerm12(): void
    {
        $fees = $this->repository->getFees(LoanTerm::TWELVE_MONTHS->value);
        $expectedFees = $this->getExpectedFeesForTerm(LoanTerm::TWELVE_MONTHS);

        $this->assertSame($expectedFees, $fees);
    }

    public function testGetFeesForTerm24(): void
    {
        $fees = $this->repository->getFees(LoanTerm::TWENTY_FOUR_MONTHS->value);
        $expectedFees = $this->getExpectedFeesForTerm(LoanTerm::TWENTY_FOUR_MONTHS);

        $this->assertSame($expectedFees, $fees);
    }

    public function testGetFeesThrowsExceptionForInvalidTerm(): void
    {
        $this->expectException(InvalidTermException::class);
        $this->expectExceptionMessage('Invalid term: 36');

        $this->repository->getFees(36);
    }
}
