<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318205438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Translation Service';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("DELETE FROM service_translation WHERE id >= 30");
        $this->addSql("
        INSERT INTO service_translation (id, service_id, language, name, description, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order) VALUES
                (30, 20, 'pl', 'Dostawa kurierem GLS', 'Wysyłka', true, null, null, null, 0),
                (31, 20, 'en', 'Delivery GLS courier', 'Delivery', true, null, null, null, 0),
                (32, 21, 'pl', 'InPost Paczkomaty 24/7', 'Wysyłka', true, null, null, null, 0),
                (33, 21, 'en', 'InPost Parcel Locker 24/7', 'Delivery', true, null, null, null, 0),
                (34, 22, 'pl', 'Odbiór Osobisty', 'Wysyłka', true, null, null, null, 0),
                (35, 22, 'en', 'Personal Pickup', 'Delivery', true, null, null, null, 0),
                (36, 23, 'pl', 'Pocztex Pickup', 'Wysyłka', true, null, null, null, 0),
                (37, 23, 'en', 'Pocztex Pickup', 'Delivery', true, null, null, null, 0),
                (38, 24, 'pl', 'DPD Pickup', 'Wysyłka', true, null, null, null, 0),
                (39, 24, 'en', 'DPD Pickup', 'Delivery', true, null, null, null, 0),
                (40, 25, 'pl', 'Dostawa Pocztex 2.0', 'Wysyłka', true, null, null, null, 0),
                (41, 25, 'en', 'Delivery Pocztex 2.0', 'Delivery', true, null, null, null, 0),
                (42, 26, 'pl', 'Dostawa kurierem DPD', 'Wysyłka', true, null, null, null, 0),
                (43, 26, 'en', 'Delivery DPD courier', 'Delivery', true, null, null, null, 0),
                (44, 27, 'pl', 'InPost kurier', 'Wysyłka', true, null, null, null, 0),
                (45, 27, 'en', 'InPost courier', 'Delivery', true, null, null, null, 0),
                (46, 28, 'pl', 'Własny kurier', 'Wysyłka', true, null, null, null, 0),
                (47, 28, 'en', 'Own Courier', 'Delivery', true, null, null, null, 0),
                (48, 29, 'pl', 'Odbiór osobisty', 'Wysyłka', true, null, null, null, 0),
                (49, 29, 'en', 'Self Pickup', 'Delivery', true, null, null, null, 0);
        ");
        $this->addSql("SELECT setval('service_translation_id_seq', 60, true);");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
