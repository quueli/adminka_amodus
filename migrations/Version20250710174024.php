<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250710174024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Создаем новую промежуточную таблицу
        $this->addSql('CREATE TABLE nomenclature_characteristic_value (id INT AUTO_INCREMENT NOT NULL, nomenclature_id INT NOT NULL, characteristic_available_value_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2CABC62B90BFD4B8 (nomenclature_id), INDEX IDX_2CABC62BB0D0726A (characteristic_available_value_id), UNIQUE INDEX nomenclature_characteristic_value_unique (nomenclature_id, characteristic_available_value_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE nomenclature_characteristic_value ADD CONSTRAINT FK_2CABC62B90BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclatures (id)');
        $this->addSql('ALTER TABLE nomenclature_characteristic_value ADD CONSTRAINT FK_2CABC62BB0D0726A FOREIGN KEY (characteristic_available_value_id) REFERENCES characteristic_available_values (id)');

        // Переносим существующие данные в новую таблицу
        $this->addSql('INSERT INTO nomenclature_characteristic_value (nomenclature_id, characteristic_available_value_id, created_at, updated_at)
                       SELECT id, characteristic_available_value_id, NOW(), NOW()
                       FROM nomenclatures
                       WHERE characteristic_available_value_id IS NOT NULL');

        // Удаляем старые связи
        $this->addSql('ALTER TABLE nomenclatures DROP FOREIGN KEY FK_9E1265E5B0D0726A');
        $this->addSql('ALTER TABLE nomenclatures DROP FOREIGN KEY FK_9E1265E5DEE9D12B');
        $this->addSql('DROP INDEX IDX_9E1265E5DEE9D12B ON nomenclatures');
        $this->addSql('DROP INDEX IDX_9E1265E5B0D0726A ON nomenclatures');
        $this->addSql('ALTER TABLE nomenclatures DROP characteristic_id, DROP characteristic_available_value_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nomenclature_characteristic_value DROP FOREIGN KEY FK_2CABC62B90BFD4B8');
        $this->addSql('ALTER TABLE nomenclature_characteristic_value DROP FOREIGN KEY FK_2CABC62BB0D0726A');
        $this->addSql('DROP TABLE nomenclature_characteristic_value');
        $this->addSql('ALTER TABLE nomenclatures ADD characteristic_id INT NOT NULL, ADD characteristic_available_value_id INT NOT NULL');
        $this->addSql('ALTER TABLE nomenclatures ADD CONSTRAINT FK_9E1265E5B0D0726A FOREIGN KEY (characteristic_available_value_id) REFERENCES characteristic_available_values (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE nomenclatures ADD CONSTRAINT FK_9E1265E5DEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristics (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9E1265E5DEE9D12B ON nomenclatures (characteristic_id)');
        $this->addSql('CREATE INDEX IDX_9E1265E5B0D0726A ON nomenclatures (characteristic_available_value_id)');
    }
}
