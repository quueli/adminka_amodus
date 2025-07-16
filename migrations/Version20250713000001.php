<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250713000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create catalog_items table with adjacency list model for hierarchical data';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE catalog_items (
            id INT AUTO_INCREMENT NOT NULL, 
            parent_id INT DEFAULT NULL, 
            name VARCHAR(255) NOT NULL, 
            base_type VARCHAR(255) DEFAULT NULL, 
            item VARCHAR(255) DEFAULT NULL, 
            location VARCHAR(255) DEFAULT NULL, 
            main_item VARCHAR(255) DEFAULT NULL, 
            item_name VARCHAR(255) DEFAULT NULL, 
            layer VARCHAR(10) DEFAULT NULL, 
            season1 TINYINT(1) DEFAULT NULL, 
            season2 TINYINT(1) DEFAULT NULL, 
            season3 TINYINT(1) DEFAULT NULL, 
            season4 TINYINT(1) DEFAULT NULL, 
            season5 TINYINT(1) DEFAULT NULL, 
            season6 TINYINT(1) DEFAULT NULL, 
            season7 TINYINT(1) DEFAULT NULL, 
            season8 TINYINT(1) DEFAULT NULL, 
            synonym LONGTEXT DEFAULT NULL, 
            construction_details VARCHAR(255) DEFAULT NULL, 
            sort_order INT DEFAULT NULL, 
            created_at DATETIME NOT NULL, 
            updated_at DATETIME DEFAULT NULL, 
            INDEX IDX_catalog_items_parent_id (parent_id), 
            INDEX IDX_catalog_items_name (name), 
            INDEX IDX_catalog_items_sort_order (sort_order), 
            INDEX IDX_catalog_items_layer (layer), 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
        $this->addSql('ALTER TABLE catalog_items ADD CONSTRAINT FK_catalog_items_parent_id FOREIGN KEY (parent_id) REFERENCES catalog_items (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE catalog_items DROP FOREIGN KEY FK_catalog_items_parent_id');
        $this->addSql('DROP TABLE catalog_items');
    }
}
