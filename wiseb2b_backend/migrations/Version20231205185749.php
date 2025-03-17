<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Wise\Service\Domain\Service\ServiceCostCalcMethodEnum;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231205185749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'set proper driver name for services and default costCalcMethod and costCalcParam
                for services which has standard driver';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            UPDATE service SET driver_name = 'delivery_standard' WHERE id_external = 'delivery';
        ");
        $this->addSql("
            UPDATE service SET driver_name = 'standard' WHERE id_external IN ('dropshipping', 'return_insurance');
        ");
        $this->addSql("
            UPDATE service SET driver_name = 'payment_standard' WHERE id_external IN ('credit_limit', 'prepayment', 'cash_on_delivery', 'payu', 'paypal', 'cash', 'postponed_payment');
        ");

        /**
         * add costCalcMethod and costCalcParam to services which has standard provider
         */
        $fixedMethod = ServiceCostCalcMethodEnum::FIXED_PRICE->value;
        $this->addSql("
            UPDATE service SET cost_calc_method = {$fixedMethod}, cost_calc_param = 0
            WHERE (driver_name = 'standard' OR driver_name = 'delivery_standard')
            AND (cost_calc_method IS NULL OR cost_calc_param IS NULL);
            ;
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            UPDATE service SET driver_name = NULL;
        ");

    }
}
