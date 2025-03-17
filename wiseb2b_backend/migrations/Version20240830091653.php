<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240830091653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'sort countries';
    }

    public function up(Schema $schema): void
    {
        //
        //commented out - causes error during github actions
        //
        /*$this->addSql('drop table if exists country_sort');
        $this->addSql('create temporary table country_sort as
                        select c.id, c.id_external, row_number() over (order by ct.name ) as sort_order
                        from country c
                        left join country_translation ct on ct.country_id = c.id and ct.language = \'pl\'
                        order by ct.name ASC ;');
        $this->addSql('update country c set sort_order = (select sort_order * 100 from country_sort c2 where c2.id_external = c.id_external);');
        $this->addSql('update country set sort_order = 0 where id_external = \'PL\';');
        */
    }

    public function down(Schema $schema): void
    {
        $this->addSql('update country set sort_order = 0;');
    }
}
