<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230809211056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add delivery methods to Client Id 1';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO client_delivery_method (id, is_active, sys_insert_date, sys_update_date, client_id, delivery_method_id) VALUES 
            ((SELECT nextval('client_delivery_method_id_seq')), true, '2023-07-31 20:05:37.000000', '2023-07-31 20:05:37.000000', 1, 3);
        ");
        $this->addSql("
            INSERT INTO client_delivery_method (id, is_active, sys_insert_date, sys_update_date, client_id, delivery_method_id) VALUES 
            ((SELECT nextval('client_delivery_method_id_seq')), true, '2023-07-31 20:05:37.000000', '2023-07-31 20:05:37.000000', 1, 7);
        ");
        $this->addSql("
            INSERT INTO client_delivery_method (id, is_active, sys_insert_date, sys_update_date, client_id, delivery_method_id) VALUES 
            ((SELECT nextval('client_delivery_method_id_seq')), true, '2023-07-31 20:05:37.000000', '2023-07-31 20:05:37.000000', 1, 4);
        ");
        $this->addSql("
            INSERT INTO client_delivery_method (id, is_active, sys_insert_date, sys_update_date, client_id, delivery_method_id) VALUES 
            ((SELECT nextval('client_delivery_method_id_seq')), true, '2023-07-31 20:05:37.000000', '2023-07-31 20:05:37.000000', 1, 5);
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            DELETE FROM client_delivery_method WHERE client_id = 1 AND delivery_method_id IN (3,7,4,5);
        ");
    }
}
