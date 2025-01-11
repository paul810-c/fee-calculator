<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Command;

use FinCalc\LoanFeeCalculator\Command\CalculateFeeCommand;
use FinCalc\LoanFeeCalculator\Common\Utils\MoneyUtils;
use FinCalc\LoanFeeCalculator\Loan\Exception\LoanValidationException;
use FinCalc\LoanFeeCalculator\Loan\Service\FeeCalculator;
use FinCalc\LoanFeeCalculator\Loan\Validator\LoanApplicationValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateFeeCommandTest extends TestCase
{
    private CommandTester $commandTester;
    private FeeCalculator $calculatorMock;
    private LoanApplicationValidator $validatorMock;

    protected function setUp(): void
    {
        $this->calculatorMock = $this->createMock(FeeCalculator::class);
        $this->validatorMock = $this->createMock(LoanApplicationValidator::class);

        $command = new CalculateFeeCommand($this->calculatorMock, $this->validatorMock);
        $this->commandTester = new CommandTester($command);
    }

    /**
     * @dataProvider validLoanProviderData
     */
    public function testCalculateFeeSuccessWithVariousInputs(int $term, float $amount, int $expectedFeeCents): void
    {
        $this->calculatorMock
            ->method('calculate')
            ->willReturn($expectedFeeCents);

        $amountInCents = MoneyUtils::toCents($amount);

        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with($term, $amountInCents);

        $this->commandTester->execute([
            'term' => $term,
            'amount' => $amount,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            sprintf('The fee is £%.2f', MoneyUtils::toPounds($expectedFeeCents)),
            $output
        );

        $this->assertSame(Command::SUCCESS, $this->commandTester->getStatusCode());
    }

    public static function validLoanProviderData(): array
    {
        return [
            [12, 1001, 550],
            [24, 20000, 750],
        ];
    }

    public function testCalculateFeeInvalidTerm(): void
    {
        $this->validatorMock
            ->method('validate')
            ->willThrowException(new LoanValidationException('Invalid loan term. Only 12 or 24 months are allowed.'));

        $this->commandTester->execute([
            'term' => 36,
            'amount' => 20000,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('Validation Error: Invalid loan term. Only 12 or 24 months are allowed.', $output);
        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeInvalidAmount(): void
    {
        $this->validatorMock
            ->method('validate')
            ->willThrowException(new LoanValidationException('Loan amount must be between £1,000 and £20,000.'));

        $this->commandTester->execute([
            'term' => 24,
            'amount' => 500,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'Validation Error: Loan amount must be between £1,000 and £20,000.',
            $output
        );

        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeUnexpectedException(): void
    {
        $this->calculatorMock
            ->method('calculate')
            ->willThrowException(new \RuntimeException('Unexpected error occurred.'));

        $this->commandTester->execute([
            'term' => 24,
            'amount' => 20000,
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString('An unexpected error occurred: Unexpected error occurred.', $output);
        $this->assertSame(Command::FAILURE, $this->commandTester->getStatusCode());
    }
}
