<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119055433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'add services - cod, credit limit, prepayment, payu, paypal';
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
                (nextval('service_id_seq'), true, 'cash_on_delivery');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Za pobraniem', 'Za pobraniem', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Cash on delivery', 'Cash on delivery', 'en');
        ");

        /**
         * add credit limit service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'credit_limit');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Kredyt kupiecki', 'Limit kredytowy', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Credit limit', 'Credit limit', 'en');
        ");

        /**
         * add prepayment service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'prepayment');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Przedpłata', 'Przedpłata', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Prepayment', 'Prepayment', 'en');
        ");

        /**
         * add payu service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'payu');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'PayU', 'PayU', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'PayU', 'PayU', 'en');
        ");

        /**
         * add paypal service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'paypal');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'PayPal', 'PayPal', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'PayPal', 'PayPal', 'en');
        ");

        /**
         * add paypal service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'cash');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Gotówka', 'Gotówka', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Cash', 'Cash', 'en');
        ");

        /**
         * add postponed payment service
         */
        $this->addSql("
            INSERT INTO service
                (id, is_active, id_external)
            VALUES
                (nextval('service_id_seq'), true, 'postponed_payment');
        ");
        $this->addSql("
            INSERT INTO service_translation
                (id, is_active, service_id, name, description, language)
            VALUES
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Płatność odroczona', 'Płatność odroczona', 'pl'),
                (nextval('service_translation_id_seq'), true, currval('service_id_seq'), 'Postponed payment', 'Postponed payment', 'en');
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("
            DELETE FROM service_translation WHERE service_id IN (SELECT id FROM service WHERE id_external IN ('cash_on_delivery', 'credit_limit', 'prepayment', 'payu', 'paypal', 'cash', 'postponed_payment'));
        ");
        $this->addSql("
            DELETE FROM service WHERE id_external IN ('cash_on_delivery', 'credit_limit', 'prepayment', 'payu', 'paypal', 'cash', 'postponed_payment');
        ");
    }
}
