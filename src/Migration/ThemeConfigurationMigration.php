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
use Contao\StringUtil;
use Contao\System;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ThemeConfigurationMigration extends AbstractMigration
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

        $test = $this->connection->fetchOne("SELECT TRUE FROM tl_theme WHERE `themeConfig` NOT LIKE '%\"article-spacing-small%' LIMIT 1");

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
        $values = $this->connection->fetchAllKeyValue("SELECT id, themeconfig FROM tl_theme WHERE themeconfig NOT LIKE '%\"article-spacing-small%'");

        foreach ($values as $id => $value)
        {
            $config = StringUtil::deserialize($value, true);

            $update = [
                'article-spacing-xs-small'  => 'article-spacing-small',
                'article-spacing-xs-medium' => 'article-spacing-medium',
                'article-spacing-xs-large'  => 'article-spacing-large'
            ];

            foreach ($update as $old => $new)
            {
                $this->setAndUpdateConfigVariable($new, $old, $config);
            }

            $this->connection->update('tl_theme', ['themeConfig' => serialize($config)], ['id' => (int) $id]);
        }

        return $this->createResult(true);
    }

    private function setAndUpdateConfigVariable(string $newKey, string $oldKey, array &$config): void
    {
        if (!isset($config[$newKey]))
        {
            $config[$newKey] = $config[$oldKey] ?? 'a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}';
            $config[$oldKey] = '$'.$newKey;
        }
    }
}
