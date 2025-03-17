<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206184003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'update global_address entity name to ...Extendable entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE global_address SET entity_name = 'receiver'
                            WHERE entity_name = 'Wise\Receiver\Domain\Receiver\Receiver'");
        $this->addSql("UPDATE global_address SET entity_name = 'user'
                            WHERE entity_name = 'Wise\User\Domain\User\User'");
        $this->addSql("UPDATE global_address SET entity_name = 'client'
                            WHERE entity_name = 'Wise\Client\Domain\Client\Client'");


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE global_address SET entity_name = 'Wise\Receiver\Domain\Receiver\Receiver'
                            WHERE entity_name = 'receiver");
        $this->addSql("UPDATE global_address SET entity_name = 'Wise\User\Domain\User\User'
                            WHERE entity_name = 'user'");
        $this->addSql("UPDATE global_address SET entity_name = 'Wise\Client\Domain\Client\Client'
                            WHERE entity_name = 'client'");
    }
}
