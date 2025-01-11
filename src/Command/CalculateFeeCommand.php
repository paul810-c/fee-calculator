<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Command;

use FinCalc\LoanFeeCalculator\Common\Contracts\FeeCalculatorInterface;
use FinCalc\LoanFeeCalculator\Common\Contracts\ValidatorInterface;
use FinCalc\LoanFeeCalculator\Common\Utils\MoneyUtils;
use FinCalc\LoanFeeCalculator\Loan\Domain\LoanApplication;
use FinCalc\LoanFeeCalculator\Loan\Exception\InvalidTermException;
use FinCalc\LoanFeeCalculator\Loan\Exception\LoanValidationException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:calculate-fee',
    description: 'Calculate loan fees based on term and amount',
)]
class CalculateFeeCommand extends Command
{
    public function __construct(
        private readonly FeeCalculatorInterface $calculator,
        private readonly ValidatorInterface $validator
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('term', InputArgument::REQUIRED, 'Loan term (12 or 24 months)')
            ->addArgument('amount', InputArgument::REQUIRED, 'Loan amount (between £1,000 and £20,000)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $executionResult = Command::SUCCESS;
        $term = (int) $input->getArgument('term');
        $amountInCents = MoneyUtils::toCents($input->getArgument('amount'));

        try {
            $this->validator->validate($term, $amountInCents);

            $application = new LoanApplication(
                term: $term,
                amountInCents: $amountInCents
            );
            $feeInCents = $this->calculator->calculate($application);

            $output->writeln(sprintf('The fee is £%.2f', MoneyUtils::toPounds($feeInCents)));
        } catch (LoanValidationException $e) {
            $output->writeln(sprintf(
                '<error>Validation Error: %s</error>',
                $e->getMessage()
            ));
            $executionResult = Command::FAILURE;
        } catch (InvalidTermException $e) {
            $output->writeln(sprintf(
                '<error>Term Error: %s</error>',
                $e->getMessage()
            ));
            $executionResult = Command::FAILURE;
        } catch (\Exception $e) {
            $output->writeln(sprintf(
                '<error>An unexpected error occurred: %s</error>',
                $e->getMessage()
            ));
            $executionResult = Command::FAILURE;
        }

        return $executionResult;
    }
}
