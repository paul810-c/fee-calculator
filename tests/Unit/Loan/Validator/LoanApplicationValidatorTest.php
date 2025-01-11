<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Validator;

use FinCalc\LoanFeeCalculator\Common\Enums\LoanTerm;
use FinCalc\LoanFeeCalculator\Loan\Exception\LoanValidationException;
use FinCalc\LoanFeeCalculator\Loan\Validator\LoanApplicationValidator;
use PHPUnit\Framework\TestCase;

class LoanApplicationValidatorTest extends TestCase
{
    private LoanApplicationValidator $validator;

    protected function setUp(): void
    {
        $this->validator = new LoanApplicationValidator();
    }

    public function testValidInputs(): void
    {
        $this->validator->validate(LoanTerm::TWELVE_MONTHS->value, 150000);
        $this->addToAssertionCount(1);
    }

    public function testInvalidTermThrowsException(): void
    {
        $this->expectException(LoanValidationException::class);
        $this->expectExceptionMessage('Invalid loan term. Only 12 or 24 months are allowed.');

        $this->validator->validate(36, 15000);
    }

    public function testInvalidAmountThrowsExceptionBelowRange(): void
    {
        $this->expectException(LoanValidationException::class);
        $this->expectExceptionMessage('Loan amount must be between £1,000 and £20,000.');

        $this->validator->validate(LoanTerm::TWELVE_MONTHS->value, 500);
    }

    public function testInvalidAmountThrowsExceptionAboveRange(): void
    {
        $this->expectException(LoanValidationException::class);
        $this->expectExceptionMessage('Loan amount must be between £1,000 and £20,000.');

        $this->validator->validate(LoanTerm::TWELVE_MONTHS->value, 25000);
    }
}
