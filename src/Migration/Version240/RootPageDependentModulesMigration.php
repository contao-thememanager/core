<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace ContaoThemeManager\Core\Migration\Version240;

use Contao\CoreBundle\Migration\AbstractMigration;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\StringUtil;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class RootPageDependentModulesMigration extends AbstractMigration
{
    private const TYPE = 'root_page_dependent_modules';
    private const SERIALIZED_INTEGER_VALUE_PATTERN = '/i:\d+;i:\d+;/';

    public function __construct(
        private readonly Connection $connection,
    ) {
    }

    /**
     * @throws Exception
     */
    public function shouldRun(): bool
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist(['tl_module'])) {
            return false;
        }

        $columns = $schemaManager->listTableColumns('tl_module');

        if (!isset($columns['type'], $columns['rootpagedependentmodules'])) {
            return false;
        }

        $values = $this->connection->fetchFirstColumn("SELECT rootPageDependentModules FROM tl_module WHERE type = ? AND rootPageDependentModules != ''", [self::TYPE]);

        foreach ($values as $value) {
            if (\is_string($value) && preg_match(self::SERIALIZED_INTEGER_VALUE_PATTERN, $value) === 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function run(): MigrationResult
    {
        $values = $this->connection->fetchAllKeyValue("SELECT id, rootPageDependentModules FROM tl_module WHERE type = ? AND rootPageDependentModules != ''", [self::TYPE]);

        foreach ($values as $id => $value) {
            $modules = StringUtil::deserialize($value, true);

            if (!$this->normalizeIntegers($modules)) {
                continue;
            }

            $this->connection->update(
                'tl_module',
                [
                    'rootPageDependentModules' => serialize($modules),
                ],
                [
                    'id' => (int) $id,
                ],
            );
        }

        return $this->createResult(true);
    }

    private function normalizeIntegers(array &$values): bool
    {
        $changed = false;

        foreach ($values as &$value) {
            if (\is_array($value)) {
                $changed = $this->normalizeIntegers($value) || $changed;

                continue;
            }

            if (!\is_int($value)) {
                continue;
            }

            $value = (string) $value;
            $changed = true;
        }

        unset($value);

        return $changed;
    }
}
