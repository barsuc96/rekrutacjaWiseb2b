<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240422080434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add new Agreement - Newsletter';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO agreement (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, symbol, is_required) VALUES
                           (nextval('agreement_id_seq'), true, null, null, null, 0, 'newsletter', false);");

        $this->addSql("
        INSERT INTO agreement_translation (id, is_active, sys_insert_date, sys_update_date, entity_hash, sort_order, language, agreement_id, name, content) VALUES
              (nextval('agreement_translation_id_seq'), true, null, null, null, 0, 'pl', currval('agreement_id_seq'), 'Newsletter', null),
              (nextval('agreement_translation_id_seq'), true, null, null, null, 0, 'en', currval('agreement_id_seq'), 'Newsletter', null);
        ");

        $this->addSql("SELECT setval('agreement_id_seq', 20, true);");
        $this->addSql("SELECT setval('agreement_translation_id_seq', 20, true);");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM agreement where symbol='newsletter'");
        $this->addSql("DELETE FROM agreement_translation where name='Newsletter'");
    }
}
