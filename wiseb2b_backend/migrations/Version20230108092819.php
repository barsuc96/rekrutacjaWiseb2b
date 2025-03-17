<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230108092819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Dodanie nowego client, dla obsługi logowania użytkowników';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            "
            INSERT INTO oauth2_client (identifier, name, secret, redirect_uris, grants, scopes, active, allow_plain_text_pkce, expiration_date)
            VALUES
                ('dea6d5hagv0c342d674o087Ef3e13E7g', '', '8eefe0a060250c96cc9ee1bada11d4069ca6553b9dae4f81180f9777866db0799010e9fe75a0244d209924d1337946393f1682a5d52e07a738dc842891d97509', null, null, 'api', true, false, '2025-03-22 00:00:00'),
                ('ff65a8109ad27bggggbe036d08b7abb9', 'Ui api', '6bgggaa06d4b9437b42c51e5ee57092a91b933450d1ce3d6d087b0855130df5b8cc188968aa357b355dfe4755c95e53cd0ea3b85ae47162e0637816736202b03', null, null, null, true, false, '2025-03-22 00:00:00');
        "
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "DELETE FROM oauth2_client WHERE identifier = 'dea6d5hagv0c342d674o087Ef3e13E7g' or identifier = 'ff65a8109ad27bggggbe036d08b7abb9';"
        );
    }
}
