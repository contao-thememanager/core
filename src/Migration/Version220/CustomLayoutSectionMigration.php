<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Migration\Version220;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class CustomLayoutSectionMigration extends AbstractMigration
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

        if (!$schemaManager->tablesExist('tl_layout'))
        {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_layout');

        if (!isset($columns['sections']))
        {
            return false;
        }

        $test = $this->connection->fetchOne("SELECT TRUE FROM tl_layout WHERE sections LIKE '%s:2:\"id\";s:10:\"main-above\";s:8:\"template\";s:13:\"block_section\";s:8:\"position\";s:6:\"before\";%' OR sections LIKE '%s:2:\"id\";s:10:\"main-below\";s:8:\"template\";s:13:\"block_section\";s:8:\"position\";s:5:\"after\";%' LIMIT 1");

        if (false !== $test)
        {
            return true;
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $values = $this->connection->fetchAllKeyValue("SELECT id, sections FROM tl_layout WHERE sections LIKE '%s:2:\"id\";s:10:\"main-above\";s:8:\"template\";s:13:\"block_section\";s:8:\"position\";s:6:\"before\";%' OR sections LIKE '%s:2:\"id\";s:10:\"main-below\";s:8:\"template\";s:13:\"block_section\";s:8:\"position\";s:5:\"after\";%'");

        foreach ($values as $id => $value)
        {
            $blnUpdate = false;

            $sections = StringUtil::deserialize($value, true);

            foreach ($sections as &$section)
            {
                if (!isset($section['id']) || !in_array($section['id'], ['main-above', 'main-below']))
                {
                    continue;
                }

                $section['position'] = 'manual';
                $blnUpdate = true;
            }

            if ($blnUpdate)
            {
                $this->connection->update('tl_layout', ['sections' => serialize($sections)], ['id' => (int) $id]);
            }
        }

        return $this->createResult(true);
    }
}
