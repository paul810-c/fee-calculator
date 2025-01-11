<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Service;

use FinCalc\LoanFeeCalculator\Loan\Service\RoundUpToNearestFive;
use PHPUnit\Framework\TestCase;

class RoundUpToNearestFiveTest extends TestCase
{
    private RoundUpToNearestFive $roundingService;

    protected function setUp(): void
    {
        $this->roundingService = new RoundUpToNearestFive();
    }

    /**
     * @dataProvider roundingDataProvider
     */
    public function testRound(int $amount, int $expected): void
    {
        $this->assertSame($expected, $this->roundingService->round($amount));
    }

    public static function roundingDataProvider(): array
    {
        return [
            [101, 105],
            [102, 105],
            [105, 105],
            [109, 110],
            [500, 500],
        ];
    }
}
