<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314161438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add delivery options to service';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("
            INSERT INTO service (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, id_external, type, cost_calc_method, cost_calc_param, driver_name) VALUES
            (20, true, null, null, null, 0, 'gls_courier_delivery', 'delivery', 1, 20, 'delivery_gls'),
            (21, true, null, null, null, 0, 'dpd_courier_delivery', 'delivery', 1, 60, 'delivery_dpd');
        ");
        $this->addSql("SELECT setval('service_id_seq', 30, true);");

        $this->addSql("
            INSERT INTO service_translation (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, service_id, language, name, description) VALUES
            (40, true, null, null, null, 0, 20, 'pl', 'GLS - Dodatkowe opcje dostawy', 'GLS - Dodatkowe opcje dostawy'),
            (41, true, null, null, null, 0, 20, 'en', 'GLS - Additional delivery options', 'GLS - Additional delivery options'),
            (42, true, null, null, null, 0, 21, 'pl', 'DPD - Dodatkowe opcje dostawy', 'DPD - Dodatkowe opcje dostawy'),
            (43, true, null, null, null, 0, 21, 'en', 'DPD - Additional delivery options', 'DPD - Additional delivery options');
        ");
        $this->addSql("SELECT setval('service_translation_id_seq', 60, true);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
