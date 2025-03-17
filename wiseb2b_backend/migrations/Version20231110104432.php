<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Wise\Service\Domain\Service\ServiceCostCalcMethodEnum;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231110104432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add example services - dropshipping and return insurance';
    }

    public function up(Schema $schema): void
    {
        /**
         * add dropshipping service
         */
        $this->addSql("
            INSERT INTO service 
                (id, is_active, id_external, cost_calc_method, cost_calc_param) 
            VALUES
                (nextval('service_id_seq'), true, 'dropshipping', ".ServiceCostCalcMethodEnum::PERCENTAGE_DISCOUNT->value.",4);
        ");
        $this->addSql("
            INSERT INTO service_translation 
                (id, is_active, service_id, name, description, language) 
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Dropshipping', 'Dropshipping (4%)', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Dropshipping', 'Dropshipping (4%)', 'en');
        ");

        /**
         * add return insurance service
         */
        $this->addSql("
            INSERT INTO service 
                (id, is_active, id_external, cost_calc_method, cost_calc_param) 
            VALUES
                (nextval('service_id_seq'), true, 'return_insurance', ".ServiceCostCalcMethodEnum::FIXED_PRICE->value.",9);
        ");
        $this->addSql("
            INSERT INTO service_translation 
                (id, is_active, service_id, name, description, language) 
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Ubezpieczenie zwrotu', 'Ubezpieczenie zwrotu (9 zł)', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Return Insurance', 'Return Insurance (9 zł)', 'en');
        ");
    }

    public function down(Schema $schema): void
    {
        /**
         * there is no need to remove services
         *
         */
    }
}
