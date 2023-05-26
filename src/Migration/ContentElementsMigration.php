<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Migration;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ContentElementsMigration extends AbstractMigration
{
    public function __construct(private readonly Connection $connection)
    {
    }

    /**
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (
            !$schemaManager->tablesExist('tl_content')
            || !array_key_exists('type', $schemaManager->listTableColumns('tl_content'))
        )
        {
            return false;
        }

        return $this->connection->fetchOne("SELECT COUNT(*) FROM tl_content WHERE type='wrapperStartBoxed'") > 0;
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $results = $this->connection->fetchAllKeyValue(
            "SELECT id, type FROM tl_content WHERE type='wrapperStartBoxed' OR type='wrapperStopBoxed'"
        );

        foreach ($results as $id => $type)
        {
            $newType = '';

            switch ($type)
            {
                case 'wrapperStartBoxed':
                    $newType = 'wrapperStartContent';
                    break;

                case 'wrapperStopBoxed':
                    $newType = 'wrapperStopContent';
            }

            if (!!$newType)
            {
                $this->connection->update('tl_content',
                    ['type' => $newType],
                    ['id'   => $id]
                );
            }
        }

        return $this->createResult(true);
    }
}
