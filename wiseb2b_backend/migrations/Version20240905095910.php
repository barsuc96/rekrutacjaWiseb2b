<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905095910 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add information about european union to country';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("UPDATE country SET in_european_union = true WHERE id_external IN ('AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'GR', 'ES', 'NL', 'IE', 'LT', 'LU', 'LV', 'MT', 'DE', 'PL', 'PT', 'RO', 'SK', 'SI', 'SE', 'HU', 'IT');");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
