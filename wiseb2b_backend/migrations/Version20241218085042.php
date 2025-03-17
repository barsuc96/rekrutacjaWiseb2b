<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218085042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add UA';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO country (id, id_external, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, in_european_union) VALUES ((SELECT nextval('country_id_seq')), 'UA', true, '2024-08-16 11:40:54', '2024-08-16 11:40:54', null, 23000, false);");
        $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order) VALUES ((SELECT nextval('country_translation_id_seq')), (SELECT currval('country_id_seq')), 'pl', 'Ukraina', true, '2024-08-16 11:40:54', '2024-08-16 11:40:54', null, 0);");
        $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order) VALUES ((SELECT nextval('country_translation_id_seq')), (SELECT currval('country_id_seq')), 'en', 'Ukraine', true, '2024-08-16 11:40:54', '2024-08-16 11:40:54', null, 0);");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
