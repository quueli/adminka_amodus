<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250716000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Упрощение структуры каталога и обновление системы сезонов с понятными названиями';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE catalog_items DROP FOREIGN KEY FK_catalog_items_parent_id');
        $this->addSql('DROP INDEX IDX_catalog_items_parent_id ON catalog_items');
        $this->addSql('DROP INDEX IDX_catalog_items_name ON catalog_items');
        $this->addSql('DROP INDEX IDX_catalog_items_sort_order ON catalog_items');
        $this->addSql('DROP INDEX IDX_catalog_items_layer ON catalog_items');

        $this->addSql('ALTER TABLE catalog_items
            ADD warm_summer TINYINT(1) DEFAULT NULL,
            ADD cool_summer_warm_spring_autumn TINYINT(1) DEFAULT NULL,
            ADD cool_spring_autumn_warm_winter TINYINT(1) DEFAULT NULL,
            ADD cold_winter TINYINT(1) DEFAULT NULL,
            ADD outer_warm_summer TINYINT(1) DEFAULT NULL,
            ADD outer_cool_summer_warm_spring_autumn TINYINT(1) DEFAULT NULL,
            ADD outer_cool_spring_autumn_warm_winter TINYINT(1) DEFAULT NULL,
            ADD outer_cold_winter TINYINT(1) DEFAULT NULL,
            DROP parent_id,
            DROP season1,
            DROP season2,
            DROP season3,
            DROP season4,
            DROP season5,
            DROP season6,
            DROP season7,
            DROP season8'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE catalog_items
            ADD season1 TINYINT(1) DEFAULT NULL,
            ADD season2 TINYINT(1) DEFAULT NULL,
            ADD season3 TINYINT(1) DEFAULT NULL,
            ADD season4 TINYINT(1) DEFAULT NULL,
            ADD season5 TINYINT(1) DEFAULT NULL,
            ADD season6 TINYINT(1) DEFAULT NULL,
            ADD season7 TINYINT(1) DEFAULT NULL,
            ADD season8 TINYINT(1) DEFAULT NULL,
            ADD parent_id INT DEFAULT NULL,
            DROP warm_summer,
            DROP cool_summer_warm_spring_autumn,
            DROP cool_spring_autumn_warm_winter,
            DROP cold_winter,
            DROP outer_warm_summer,
            DROP outer_cool_summer_warm_spring_autumn,
            DROP outer_cool_spring_autumn_warm_winter,
            DROP outer_cold_winter'
        );

        $this->addSql('ALTER TABLE catalog_items ADD CONSTRAINT FK_C7F8F727727ACA70 FOREIGN KEY (parent_id) REFERENCES catalog_items (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_C7F8F727727ACA70 ON catalog_items (parent_id)');
    }
}
