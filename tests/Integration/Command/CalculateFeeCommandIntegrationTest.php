<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Integration\Command;

use FinCalc\LoanFeeCalculator\Command\CalculateFeeCommand;
use FinCalc\LoanFeeCalculator\Loan\Repository\InMemoryFeeRepository;
use FinCalc\LoanFeeCalculator\Loan\Service\FeeCalculator;
use FinCalc\LoanFeeCalculator\Loan\Service\BoundFinder;
use FinCalc\LoanFeeCalculator\Loan\Service\LinearInterpolation;
use FinCalc\LoanFeeCalculator\Loan\Service\RoundUpToNearestFive;
use FinCalc\LoanFeeCalculator\Loan\Validator\LoanApplicationValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class CalculateFeeCommandIntegrationTest extends TestCase
{
    private CommandTester $commandTester;

    protected function setUp(): void
    {
        // Instantiate all dependencies
        $repository = new InMemoryFeeRepository();
        $boundFinder = new BoundFinder();
        $interpolationStrategy = new LinearInterpolation();
        $roundingService = new RoundUpToNearestFive();
        $validator = new LoanApplicationValidator();

        // Pass all dependencies to FeeCalculator
        $calculator = new FeeCalculator(
            repository: $repository,
            boundFinder: $boundFinder,
            interpolationStrategy: $interpolationStrategy,
            roundingService: $roundingService
        );

        // Create the command with the FeeCalculator and Validator
        $command = new CalculateFeeCommand($calculator, $validator);
        $this->commandTester = new CommandTester($command);
    }

    public function testCalculateFeeWithMissingArguments(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Not enough arguments (missing: "term, amount")');

        $this->commandTester->execute([]);

        $this->commandTester->getDisplay();
    }

    public function testCalculateFeeWithStringTerm(): void
    {
        $this->commandTester->execute([
            'term' => 'test',
            'amount' => '15000',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'Validation Error: Invalid loan term. Only 12 or 24 months are allowed.',
            $output
        );

        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeWithStringAmount(): void
    {
        $this->commandTester->execute([
            'term' => '12',
            'amount' => 'invalid_amount',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'Validation Error: Loan amount must be between £1,000 and £20,000.',
            $output
        );

        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeWithInvalidTerm(): void
    {
        $this->commandTester->execute([
            'term' => '36',
            'amount' => '15000',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'Validation Error: Invalid loan term. Only 12 or 24 months are allowed.',
            $output
        );

        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeWithInvalidAmount(): void
    {
        $this->commandTester->execute([
            'term' => '24',
            'amount' => '500', // Below valid range
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'Validation Error: Loan amount must be between £1,000 and £20,000.',
            $output
        );

        $this->assertSame(1, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeWithValidInputs(): void
    {
        $this->commandTester->execute([
            'term' => '24',
            'amount' => '15000',
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'The fee is £600.00',
            $output
        );

        $this->assertSame(0, $this->commandTester->getStatusCode());
    }

    public function testCalculateFeeWithExactBreakpoint(): void
    {
        $this->commandTester->execute([
            'term' => '12',
            'amount' => '2000', // Exact breakpoint
        ]);

        $output = $this->commandTester->getDisplay();

        $this->assertStringContainsString(
            'The fee is £90.00',
            $output
        );

        $this->assertSame(0, $this->commandTester->getStatusCode());
    }
}
