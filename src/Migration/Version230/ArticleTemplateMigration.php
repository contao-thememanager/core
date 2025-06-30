<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Migration\Version230;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class ArticleTemplateMigration extends AbstractMigration
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

        if (!$schemaManager->tablesExist('tl_article'))
        {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_article');

        if (!isset($columns['customtpl']))
        {
            return false;
        }

        $test = $this->connection->fetchOne("SELECT TRUE FROM tl_article WHERE customTpl = 'mod_article_contao53_default' LIMIT 1");

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
        $values = $this->connection->fetchAllKeyValue("SELECT id, customTpl FROM tl_article WHERE customTpl = 'mod_article_contao53_default'");

        foreach ($values as $id => $value)
        {
            $this->connection->update('tl_article', ['customTpl' => 'mod_article_default'], ['id' => (int) $id]);
        }

        return $this->createResult(true);
    }
}
