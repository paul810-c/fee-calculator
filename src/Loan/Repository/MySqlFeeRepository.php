<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Loan\Repository;

use FinCalc\LoanFeeCalculator\Common\Contracts\FeeRepositoryInterface;
use PDO;

final class MySqlFeeRepository implements FeeRepositoryInterface
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function getFees(int $term): array
    {
        $query = 'SELECT amount, fee FROM fees WHERE term = :term';

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->bindValue(':term', $term, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                throw new \RuntimeException(sprintf('No fees found for term: %d', $term));
            }

            return array_column($rows, 'fee', 'amount');
        } catch (\PDOException $e) {
            throw new \RuntimeException('Failed to retrieve fees: ' . $e->getMessage(), 0, $e);
        }
    }
}
