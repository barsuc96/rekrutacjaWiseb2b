<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301164037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'dtype column to client, receiver, category, and productattachment tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE client ADD IF NOT EXISTS dtype VARCHAR(255) NOT null default \'client\';');
        $this->addSql('ALTER TABLE receiver ADD IF NOT EXISTS dtype VARCHAR(255) NOT null default \'receiver\';');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE client DROP IF EXISTS dtype');
        $this->addSql('ALTER TABLE receiver DROP IF EXISTS dtype');
    }
}
