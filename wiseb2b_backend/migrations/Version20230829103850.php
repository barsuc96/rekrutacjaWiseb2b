<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230829103850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Uzupełnienie kodu kraju PL dla nieprawidłowych adresów i adresów w koszyku';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            UPDATE global_address SET country_code = 'pl' WHERE country_code IS NULL;
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
