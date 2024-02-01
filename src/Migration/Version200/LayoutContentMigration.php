<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Migration\Version200;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class LayoutContentMigration extends AbstractMigration
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

        if (!$schemaManager->tablesExist('tl_theme'))
        {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_theme');

        if (!isset($columns['themeconfig']))
        {
            return false;
        }

        $test = $this->connection->fetchOne("SELECT TRUE FROM tl_theme WHERE `themeConfig` NOT LIKE '%\"activate-content-heading-settings%' LIMIT 1");

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
        $values = $this->connection->fetchAllKeyValue("SELECT id, themeconfig FROM tl_theme WHERE themeconfig NOT LIKE '%\"activate-content-heading-settings%'");

        foreach ($values as $id => $value)
        {
            $config = StringUtil::deserialize($value, true);

            $config['activate-content-heading-settings'] = true;

            $this->connection->update('tl_theme', ['themeConfig' => serialize($config)], ['id' => (int) $id]);
        }

        return $this->createResult(true);
    }
}
