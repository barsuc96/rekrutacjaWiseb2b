<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240814122707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'UPDATE information about country in European Union';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        foreach ($this->getListOfCountries() as $countryId) {
            $this->addSql("UPDATE country SET in_european_union = true WHERE id_external = '$countryId';");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE country SET in_european_union = false WHERE id > 1;");
    }


    protected function getListOfCountries(): array
    {
        return [
            'AT', // Austria
            'BE', // Belgia
            'BG', // Bułgaria
            'HR', // Chorwacja
            'CY', // Cypr
            'CZ', // Czechy
            'DK', // Dania
            'EE', // Estonia
            'FI', // Finlandia
            'FR', // Francja
            'DE', // Niemcy
            'GR', // Grecja
            'HU', // Węgry
            'IE', // Irlandia
            'IT', // Włochy
            'LV', // Łotwa
            'LT', // Litwa
            'LU', // Luksemburg
            'MT', // Malta
            'NL', // Holandia
            'PL', // Polska
            'PT', // Portugalia
            'RO', // Rumunia
            'SK', // Słowacja
            'SI', // Słowenia
            'ES', // Hiszpania
            'SE', // Szwecja
        ];
    }
}
