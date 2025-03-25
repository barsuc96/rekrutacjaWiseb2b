<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250324132608 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
      
        $this->addSql("INSERT INTO supplier(id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, payload_bag, id_external, symbol, tax_number, email, phone, registered_trade_name, dtype) 
        VALUES((SELECT nextval('supplier_id_seq')), true, '2025-12-24 12:12:12', '2025-12-24 12:12:12', 'asdasd', 0, NULL, NULL, 'WiseB2B2', '1234567890', 'przykladowy_jan_kowalski@example.com', '000111999', 'WiseB2B Sp. z o.o.', 'gpsrsupplier')");

        
        $this->addSql("INSERT INTO global_address(id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, payload_bag, entity_name, field_name, entity_id, name, street, house_number, apartment_number, city, postal_code, country_code, state)
        VALUES((SELECT nextval('global_address_id_seq')), true, '2025-03-24 12:43:30.000', '2025-03-24 12:43:30.000', NULL, 0, NULL, 'supplier', 'address', (SELECT currval('supplier_id_seq')), 'Domowy2', 'Przykładowa', '44', '26', 'Warszawa', '00-000', 'pl', 'example')");
        
    }

    public function down(Schema $schema): void
    {
       
    }
}