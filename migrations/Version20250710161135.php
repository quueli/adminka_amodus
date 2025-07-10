<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710161135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE characteristic_available_values (id INT AUTO_INCREMENT NOT NULL, characteristic_id INT NOT NULL, value VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BD7310E5DEE9D12B (characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE characteristics (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nomenclatures (id INT AUTO_INCREMENT NOT NULL, characteristic_id INT NOT NULL, characteristic_available_value_id INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9E1265E5DEE9D12B (characteristic_id), INDEX IDX_9E1265E5B0D0726A (characteristic_available_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE characteristic_available_values ADD CONSTRAINT FK_BD7310E5DEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristics (id)');
        $this->addSql('ALTER TABLE nomenclatures ADD CONSTRAINT FK_9E1265E5DEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristics (id)');
        $this->addSql('ALTER TABLE nomenclatures ADD CONSTRAINT FK_9E1265E5B0D0726A FOREIGN KEY (characteristic_available_value_id) REFERENCES characteristic_available_values (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE characteristic_available_values DROP FOREIGN KEY FK_BD7310E5DEE9D12B');
        $this->addSql('ALTER TABLE nomenclatures DROP FOREIGN KEY FK_9E1265E5DEE9D12B');
        $this->addSql('ALTER TABLE nomenclatures DROP FOREIGN KEY FK_9E1265E5B0D0726A');
        $this->addSql('DROP TABLE characteristic_available_values');
        $this->addSql('DROP TABLE characteristics');
        $this->addSql('DROP TABLE nomenclatures');
    }
}
