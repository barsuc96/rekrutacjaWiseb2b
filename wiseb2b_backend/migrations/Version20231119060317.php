<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119060317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add delivery service';
    }

    public function up(Schema $schema): void
    {
        /**
         * add cod service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'delivery');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Dostawa', 'Usługa dostawy', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Delivery', 'Delivery service', 'en');
        ");


    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            DELETE FROM service_translation WHERE service_id IN (SELECT id FROM service WHERE id_external IN ('delivery'));
        ");
        $this->addSql("
            DELETE FROM service WHERE id_external IN ('delivery');
        ");
    }
}
