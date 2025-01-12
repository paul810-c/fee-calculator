<?php

declare(strict_types=1);

namespace FinCalc\LoanFeeCalculator\Tests\Unit\Loan\Repository;

use FinCalc\LoanFeeCalculator\Loan\Repository\MySqlFeeRepository;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class MySqlFeeRepositoryTest extends TestCase
{
    private PDO $pdoMock;
    private PDOStatement $statementMock;
    private MySqlFeeRepository $repository;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->statementMock = $this->createMock(PDOStatement::class);
        $this->repository = new MySqlFeeRepository($this->pdoMock);
    }

    public function testGetFeesReturnsCorrectData(): void
    {
        $term = 12;
        $expectedResult = [
            1000 => 50,
            2000 => 90,
        ];
        $dbRows = [
            ['amount' => 1000, 'fee' => 50],
            ['amount' => 2000, 'fee' => 90],
        ];

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT amount, fee FROM fees WHERE term = :term')
            ->willReturn($this->statementMock);

        $this->statementMock
            ->expects($this->once())
            ->method('bindValue')
            ->with(':term', $term, PDO::PARAM_INT);

        $this->statementMock
            ->expects($this->once())
            ->method('execute');

        $this->statementMock
            ->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn($dbRows);

        $result = $this->repository->getFees($term);

        $this->assertSame($expectedResult, $result);
    }

    public function testGetFeesThrowsExceptionWhenNoRowsFound(): void
    {
        $term = 12;

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT amount, fee FROM fees WHERE term = :term')
            ->willReturn($this->statementMock);

        $this->statementMock
            ->expects($this->once())
            ->method('bindValue')
            ->with(':term', $term, PDO::PARAM_INT);

        $this->statementMock
            ->expects($this->once())
            ->method('execute');

        $this->statementMock
            ->expects($this->once())
            ->method('fetchAll')
            ->with(PDO::FETCH_ASSOC)
            ->willReturn([]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(sprintf('No fees found for term: %d', $term));

        $this->repository->getFees($term);
    }

    public function testGetFeesThrowsExceptionOnPdoFailure(): void
    {
        $term = 12;

        $this->pdoMock
            ->expects($this->once())
            ->method('prepare')
            ->with('SELECT amount, fee FROM fees WHERE term = :term')
            ->willThrowException(new \PDOException('Database error'));

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Failed to retrieve fees: Database error');

        $this->repository->getFees($term);
    }
}
