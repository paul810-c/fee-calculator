<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFeesTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('fees')
            ->addColumn('term', 'integer')
            ->addColumn('amount', 'integer')
            ->addColumn('fee', 'integer')
            ->create();
    }
}
