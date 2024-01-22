<?php

declare(strict_types=1);

/*
 * This file is part of Contao ThemeManager Core.
 *
 * (c) https://www.oveleon.de/
 */

namespace Migration;

use Contao\CoreBundle\Migration\MigrationCollection;
use Contao\CoreBundle\Migration\MigrationResult;
use Contao\Database;
use Contao\Database\Result;
use Contao\Database\Statement;
use Contao\TestCase\ContaoTestCase;
use Contao\System;
use ContaoThemeManager\Core\Migration\ThemeConfigurationMigration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\Schema;

class ThemeConfigurationMigrationTest extends ContaoTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $schemaManager = $this->createMock(AbstractSchemaManager::class);
        $schemaManager
            ->method('introspectSchema')
            ->willReturn(new Schema())
        ;

        $connection = $this->createMock(Connection::class);
        $connection
            ->method('quoteIdentifier')
            ->willReturnArgument(0)
        ;

        $connection
            ->method('createSchemaManager')
            ->willReturn($schemaManager)
        ;

        $container = $this->getContainerWithContaoConfiguration();
        $container->set('database_connection', $connection);

        System::setContainer($container);
    }

    protected function tearDown(): void
    {
        $this->resetStaticProperties([System::class]);

        parent::tearDown();
    }

    public function testHasPendingMigrations()
    {
        $migrations = new MigrationCollection($this->getMigrationServices());

        $this->assertTrue($migrations->hasPending());
    }

    public function testGetPendingNames(): void
    {
        $migrations = new MigrationCollection($this->getMigrationServices());
        $pendingMigrations = $migrations->getPendingNames();

        if ($pendingMigrations instanceof \Traversable) {
            $pendingMigrations = iterator_to_array($pendingMigrations);
        }

        $this->assertSame(['Successful Migration', 'Failing Migration'], $pendingMigrations);
    }

    public function testRunMigrations(): void
    {
        $migrations = new MigrationCollection($this->getMigrationServices());
        $results = $migrations->run();

        if ($results instanceof \Traversable) {
            $results = iterator_to_array($results);
        }

        $this->assertCount(2, $results);
        $this->assertInstanceOf(MigrationResult::class, $results[0]);
        $this->assertTrue($results[0]->isSuccessful());
        $this->assertSame('successful', $results[0]->getMessage());
        $this->assertInstanceOf(MigrationResult::class, $results[1]);
        $this->assertFalse($results[1]->isSuccessful());
        $this->assertSame('failing', $results[1]->getMessage());
    }

    public function testDoesNotRunIfNoMappings(): void
    {
        $db = $this->createMock(Connection::class);
        $migration = new ThemeConfigurationMigration($db);

        $this->assertFalse($migration->shouldRun());
    }

    /*public function testMigrateThemeManagerConfiguration(): void
    {
        $themeConfigurations = [
            ['id' => 1, 'themeConfig' => 'a:3:{s:21:"article-spacing-small";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";s:22:"article-spacing-medium";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";s:21:"article-spacing-large";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";}'],
            ['id' => 2, 'themeConfig' => 'a:3:{s:24:"article-spacing-xs-small";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";s:25:"article-spacing-xs-medium";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";s:24:"article-spacing-xs-large";s:49:"a:2:{s:4:"unit";s:3:"rem";s:5:"value";s:3:"2.5";}";}'],
        ];
    }*/

    private function getMigrationServices(): array
    {
        $connection = System::getContainer()->get('database_connection');

        return [
            new class($connection) extends ThemeConfigurationMigration {
                public function getName(): string
                {
                    return 'Successful Migration';
                }

                public function shouldRun(): bool
                {
                    return true;
                }

                public function run(): MigrationResult
                {
                    return $this->createResult(true, 'successful');
                }
            },
            new class($connection) extends ThemeConfigurationMigration {
                public function getName(): string
                {
                    return 'Failing Migration';
                }

                public function shouldRun(): bool
                {
                    return true;
                }

                public function run(): MigrationResult
                {
                    return $this->createResult(false, 'failing');
                }
            },
            new class($connection) extends ThemeConfigurationMigration {
                public function getName(): string
                {
                    return 'Inactive Migration';
                }

                public function shouldRun(): bool
                {
                    return false;
                }

                public function run(): MigrationResult
                {
                    throw new \LogicException('Should never be executed');
                }
            },
        ];
    }

    private function mockDatabase(Database $database): void
    {
        $property = (new \ReflectionClass($database))->getProperty('objInstance');
        $property->setValue(null, $database);

        $this->assertSame($database, Database::getInstance());
    }
}
