<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Validator;

use FinCalc\LoanFeeCalculator\Common\Contracts\ValidatorInterface;
use FinCalc\LoanFeeCalculator\Common\Enums\LoanAmountRange;
use FinCalc\LoanFeeCalculator\Common\Enums\LoanTerm;
use FinCalc\LoanFeeCalculator\Common\Utils\MoneyUtils;
use FinCalc\LoanFeeCalculator\Loan\Exception\LoanValidationException;

class LoanApplicationValidator implements ValidatorInterface
{
    public function validate(int $term, int $amount): void
    {
        $this->validateTerm($term);
        $this->validateAmount($amount);
    }

    private function validateTerm(int $term): void
    {
        $validTerms = array_column(LoanTerm::cases(), 'value');
        if (!in_array($term, $validTerms, true)) {
            throw new LoanValidationException('Invalid loan term. Only 12 or 24 months are allowed.');
        }
    }

    private function validateAmount(int $amount): void
    {
        $amountInPounds = MoneyUtils::toPounds($amount);
        if ($amountInPounds < LoanAmountRange::MIN->value || $amountInPounds > LoanAmountRange::MAX->value) {
            throw new LoanValidationException('Loan amount must be between £1,000 and £20,000.');
        }
    }
}
