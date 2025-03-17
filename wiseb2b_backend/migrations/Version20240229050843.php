<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229050843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add oauth2_client table and insert data for client api tokens.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
            INSERT INTO oauth2_client (identifier, name, secret, redirect_uris, grants, scopes, active, allow_plain_text_pkce, expiration_date)
            VALUES ('ceaf10768d5d2a2f8fc385c51584832c', 'Client api', 'c4385878282092f9c1b967747a053b0269b077bc82e05765c6f57794828c141678479ef9aacc97b00fa6a1d27c78cc72bb08857b3b07d74fb73c7e8b0f53e235', null, null, 'client-api', true, false, '2300-03-22 00:00:00');");}

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            "DELETE FROM oauth2_client WHERE identifier = 'ceaf10768d5d2a2f8fc385c51584832c';"
        );
    }
}
