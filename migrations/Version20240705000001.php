<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to create colors table for color management admin panel
 */
final class Version20240705000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create colors table with Russian field names for color management';
    }

    public function up(Schema $schema): void
    {
        // Drop old table_records table if it exists
        if ($schema->hasTable('table_records')) {
            $schema->dropTable('table_records');
        }

        // Create new colors table
        $table = $schema->createTable('colors');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('color', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('hex_color_number', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('saturation', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('palette', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);

        // Set table options for MySQL
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collate', 'utf8mb4_unicode_ci');
        $table->addOption('engine', 'InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Drop colors table
        if ($schema->hasTable('colors')) {
            $schema->dropTable('colors');
        }

        // Recreate old table_records table structure
        $table = $schema->createTable('table_records');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('field1', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('field2', 'string', ['length' => 255, 'notnull' => true]);
        $table->addColumn('field3', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('field4', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('field5', 'string', ['length' => 255, 'notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['notnull' => true]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => true]);
        $table->setPrimaryKey(['id']);

        // Set table options for MySQL
        $table->addOption('charset', 'utf8mb4');
        $table->addOption('collate', 'utf8mb4_unicode_ci');
        $table->addOption('engine', 'InnoDB');
    }
}
