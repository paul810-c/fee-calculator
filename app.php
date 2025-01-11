<?php

require __DIR__ . '/vendor/autoload.php';

use FinCalc\LoanFeeCalculator\Command\CalculateFeeCommand;
use FinCalc\LoanFeeCalculator\Loan\Repository\InMemoryFeeRepository;
use FinCalc\LoanFeeCalculator\Loan\Repository\MySqlFeeRepository;
use FinCalc\LoanFeeCalculator\Loan\Service\BoundFinder;
use FinCalc\LoanFeeCalculator\Loan\Service\FeeCalculator;
use FinCalc\LoanFeeCalculator\Loan\Service\LinearInterpolation;
use FinCalc\LoanFeeCalculator\Loan\Validator\LoanApplicationValidator;
use FinCalc\LoanFeeCalculator\Loan\Service\RoundUpToNearestFive;
use Symfony\Component\Console\Application;

$application = new Application();

// $repository = new InMemoryFeeRepository();

$dsn = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
    getenv('MYSQL_HOST'),
    getenv('MYSQL_PORT'),
    getenv('MYSQL_DATABASE')
);
$pdo = new PDO($dsn, getenv('MYSQL_USER'), getenv('MYSQL_PASSWORD'));
$repository = new MySqlFeeRepository($pdo);
$boundFinder = new BoundFinder();
$interpolationStrategy = new LinearInterpolation();
$roundingService = new RoundUpToNearestFive();
$validator = new LoanApplicationValidator();

$calculator = new FeeCalculator(
    repository: $repository,
    boundFinder: $boundFinder,
    interpolationStrategy: $interpolationStrategy,
    roundingService: $roundingService
);

$command = new CalculateFeeCommand($calculator, $validator);
$application->add($command);

$application->run();
