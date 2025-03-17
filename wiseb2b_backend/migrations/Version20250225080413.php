<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225080413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'fill country AE';
    }

    public function up(Schema $schema): void
    {
        foreach ($this->getCountryList() as $iso => $country){
            $this->addSql("INSERT INTO country (id, id_external, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_id_seq')), '{$iso}', true, NOW(), NOW())");
            $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = '{$iso}' LIMIT 1), 'pl', '{$country['pl']}', true, NOW(), NOW())");
            $this->addSql("INSERT INTO country_translation (id, country_id, language, name, is_active, sys_insert_date, sys_update_date) VALUES
                        ((SELECT nextval('country_translation_id_seq')), (SELECT id FROM country WHERE id_external = '{$iso}' LIMIT 1), 'en', '{$country['en']}', true, NOW(), NOW())");
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->getCountryList() as $iso => $country){
            $this->addSql("DELETE FROM country_translation WHERE country_id = (SELECT id FROM country WHERE id_external = '{$iso}' LIMIT 1)");
            $this->addSql("DELETE FROM country WHERE id_external = '{$iso}'");
        }
    }

    private function getCountryList()
    {
        return [
            'AE' => ['pl' => 'Zjednoczone Emiraty Arabskie', 'en' => 'United Arab Emirates'],
        ];
    }
}
