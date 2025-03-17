<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010112300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Poprawa testowych danych klienta (np. aktualizacja is_active)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("update client set is_active = true;");
        $this->addSql("update client set pricelist_id = 5 where id=1;");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
