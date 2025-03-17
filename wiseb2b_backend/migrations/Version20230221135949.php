<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221135949 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migracja danych z Wise 1.5: klienci, role użytkowników, użytkownicy, dokumenty, pliki dokumentów, płatności(settlement)';
    }

    public function up(Schema $schema): void
    {
        // client
        $this->addSql("delete from client;");
        $this->addSql("insert into client (id, id_external, name, client_parent_id, email, phone, tax_number, default_payment_method_id,
                    default_delivery_method_id, flags, trade_credit_total, trade_credit_free, default_currency, type,
                    dropshipping_cost, order_return_cost, free_delivery_limit, is_active, sys_insert_date, dtype)
values (1, '109', 'Sente SENTE Informatyczne', 0, 'biuro@sente.pl', null, '23', 0, 0, null, 0, 0, 'PLN', null, null,
        null, null, null, current_timestamp, 'client'),
       (2, '111', 'System Client', 0, 'system_client@wiseb2b.eu', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (3, '112', 'Ernest', 0, 'maetusz201@sente.pl', '50487426575', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (4, '115', 'KYOTU', 0, 'biuro@kyotu.pl', null, '23', 0, 0, null, 0, 0, 'PLN', null, null, null, null, null,
        current_timestamp, 'client'),
       (5, '114', 'DK company', 0, 'dd1223@sente.pl', '123456789', '23', 0, 0, null, 0, 0, null, null, null, null, null,
        null, current_timestamp, 'client'),
       (6, '117', 'Przykladowa 1', 0, 'qwe@qwe.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null, null,
        null, current_timestamp, 'client'),
       (7, '118', 'string', 0, 'string', 'string', '23', 0, 0, null, 0, 0, null, null, null, null, null, null,
        current_timestamp, 'client'),
       (8, '119', '555', 0, 'string', 'string', '23', 0, 0, null, 0, 0, null, null, null, null, null, null,
        current_timestamp, 'client'),
       (9, '120', 'Klient testowy z API', 0, 'p.fudali@sente.pl', '123124123', '23', 0, 0, null, 0, 0, null, null, null,
        null, null, null, current_timestamp, 'client'),
       (10, '121', 'Klient testowy z API', 0, 'p.fudali@sente.pl', '123124123', '23', 0, 0, null, 0, 0, null, null,
        null, null, null, null, current_timestamp, 'client'),
       (11, '122', 'Klient testowy z API_1', 0, 'p.testowy@sente.pl', '123124123', '23', 0, 0, null, 0, 0, null, null,
        null, null, null, null, current_timestamp, 'client'),
       (12, '123', 'Klient_1', 0, 'p.fudali@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (13, '124', 'Klient_1', 0, 'p.fudali@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (14, '125', 'Klient_1', 0, 'p.fudali@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (15, '126', 'Piotr Testowy z API', 0, 'p.@fudali@sente.pl', '1581581587', '23', 0, 0, null, 0, 0, null, null,
        null, null, null, null, current_timestamp, 'client'),
       (16, '127', 'Firma testowa PF_Test', 0, 'p.test@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null,
        null, null, null, current_timestamp, 'client'),
       (17, '128', 'asdfasdf', 0, 'asdfasdf@sdfgsdfgsdfgh.', 'dfgdfgdfgdfg', '23', 0, 0, null, 0, 0, null, null, null,
        null, null, null, current_timestamp, 'client'),
       (18, '129', 'Firma testing', 0, 'p.testy@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (19, '130', 'NazwaFirmy', 0, 'p.dddddd@sente.pl', '123123123', '23', 0, 0, null, 0, 0, null, null, null, null,
        null, null, current_timestamp, 'client'),
       (20, '131', 'ZmianaNazyw klient nowy', 0, 'test1231@wp.pl', '123123123', '23', 0, 0, null, 0, 0, null, null,
        null, null, null, null, current_timestamp, 'client');");
        $this->addSql("SELECT setval('client_id_seq', 20, true);");

        // user
        $this->addSql("delete from \"user\";");
        $this->addSql("INSERT INTO \"user\" (id, id_external, client_id, role_id, trader_id, login, password, create_date, first_name, last_name,
                    email, phone, is_active, sys_insert_date)
values (1, 164, 1, 5, 3, 'biuro@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan', 'Kowalski',
        'biuro@sente.pl', null, true, current_timestamp),
       (2, 169, 2, 2, 3, 'system_user@wiseb2b.eu', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Użytkownik',
        'Systemowy', 'system_user@wiseb2b.eu', null, true, current_timestamp),
       (3, 170, 1, 3, 2, 'heniek@poczta.onet.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Heniek',
        'Wcisło', 'heniek@poczta.onet.pl', null, true, current_timestamp),
       (4, 171, 3, 2, 1, 'maetusz201@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Ernest',
        'Mateusz', 'maetusz201@sente.pl', null, true, current_timestamp),
       (5, 172, 1, 3, 2, 'e.mazur@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Ernest', 'Mazur',
        'e.mazur@sente.pl', null, true, current_timestamp),
       (6, 173, 1, 3, 2, 'd.kowalczyk@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Damian',
        'Kowalczyk', 'd.kowalczyk@sente.pl', null, true, current_timestamp),
       (7, 176, 1, 3, 2, 'p.herder@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'PaweÄąâ€š',
        'Herder', 'p.herder@sente.pl', null, true, current_timestamp),
       (8, 177, 1, 3, 2, 'test@test.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'anna', 'test',
        'test@test.pl', null, true, current_timestamp),
       (9, 175, 5, 1, 2, 'dd1223@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'di', 'kej',
        'dd1223@sente.pl', null, true, current_timestamp),
       (10, 178, 1, 3, 2, 'nowy@uzytkownik.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'anna', 'test',
        'nowy@uzytkownik.pl', null, true, current_timestamp),
       (11, 180, 4, 1, 1, 'kyotu@kyotu.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'John', 'Kowalsky',
        'kyotu@kyotu.pl', null, true, current_timestamp),
       (12, 181, 4, 5, 3, 'admin@kyotu.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Admin', 'Admin',
        'admin@kyotu.pl', null, true, current_timestamp),
       (13, 182, 1, 2, 3, 'biuro_\"user\"@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan',
        '\"user\"', 'biuro_\"user\"@sente.pl', null, true, current_timestamp),
       (14, 184, 1, 1, 1, 'biuro_power\"user\"@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan',
        'Power\"user\"', 'biuro_power\"user\"@sente.pl', null, true, current_timestamp),
       (15, 185, 1, 3, 1, 'biuro_sales@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan',
        'Sales', 'biuro_sales@sente.pl', null, true, current_timestamp),
       (16, 187, 9, 1, 2, 'p.fudali@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Administrator',
        '', 'p.fudali@sente.pl', null, true, current_timestamp),
       (17, 188, 11, 1, 1, 'p.testowy@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp,
        'Administrator', '', 'p.testowy@sente.pl', null, true, current_timestamp),
       (18, 186, 1, 5, 2, 'biuro_sysadmin@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan',
        'Sysadmin', 'biuro_sysadmin@sente.pl', null, true, current_timestamp),
       (19, 189, 1, 3, 1, 'pfudali@test1.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piotr',
        'Testing', 'pfudali@test1.pl', null, true, current_timestamp),
       (20, 191, 1, 6, 3, 'open_profile@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Jan',
        'Openprofile', 'open_profile@sente.pl', null, true, current_timestamp),
       (21, 192, 15, 1, 1, 'p.@fudali@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp,
        'Administrator', '', 'p.@fudali@sente.pl', null, true, current_timestamp),
       (22, 193, 16, 1, 1, 'p.test@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Administrator',
        '', 'p.test@sente.pl', null, true, current_timestamp),
       (23, 194, 1, 3, 1, 'b.izdebski@mindz.it', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Bart', 'I',
        'b.izdebski@mindz.it', null, true, current_timestamp),
       (24, 195, 1, 3, 2, '', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, '', '', '', null, true,
        current_timestamp),
       (25, 196, 1, 3, 3, 'dff', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, '', '', 'dff', null, true,
        current_timestamp),
       (26, 197, 1, 3, 2, 'sdfsdf', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, '', '', 'sdfsdf', null,
        true, current_timestamp),
       (27, 198, 1, 2, 1, 'p.fudali1@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piotr\"user\"',
        'Testowe', 'p.fudali1@sente.pl', null, true, current_timestamp),
       (28, 199, 1, 2, 2, 'p.fudali2@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piotr\"user\"',
        'Testowe', 'p.fudali2@sente.pl', null, true, current_timestamp),
       (29, 200, 1, 2, 2, 'pfudali3@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'piotrtest1',
        'testowe', 'pfudali3@sente.pl', null, true, current_timestamp),
       (30, 201, 1, 2, 1, 'pfudali4@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'piotrtest1',
        'testowe', 'pfudali4@sente.pl', null, true, current_timestamp),
       (31, 202, 1, 2, 3, 'pfudali5@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'piotrtest1',
        'testowe', 'pfudali5@sente.pl', null, true, current_timestamp),
       (32, 203, 1, 2, 1, 'te11st@test.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'test1', 'test1',
        'te11st@test.pl', null, true, current_timestamp),
       (33, 204, 1, 3, 2, 'asdf', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'adsfg', 'adsfg', 'asdf',
        null, true, current_timestamp),
       (34, 205, 1, 3, 2, 'asdfasdfasdf', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'asdfasdf',
        'asdfasdf', 'asdfasdfasdf', null, true, current_timestamp),
       (35, 206, 1, 3, 3, 'email@lk;ajhsdflkjahsd;kfjh.sdfsdf', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp,
        'imie', 'nazwisko', 'email@lk;ajhsdflkjahsd;kfjh.sdfsdf', null, true, current_timestamp),
       (36, 207, 17, 1, 1, 'asdfasdf@sdfgsdfgsdfgh.', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp,
        'Administrator', '', 'asdfasdf@sdfgsdfgsdfgh.', null, true, current_timestamp),
       (37, 208, 1, 2, 2, 'testemail@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Nowypf',
        'testowe', 'testemail@wp.pl', null, true, current_timestamp),
       (38, 209, 1, 2, 3, 'p.fudali@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piotr', 'Testing',
        'p.fudali@wp.pl', null, true, current_timestamp),
       (39, 210, 1, 2, 1, 'test1212@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'test1', 'test2',
        'test1212@wp.pl', null, true, current_timestamp),
       (40, 211, 18, 1, 1, 'p.testy@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Administrator',
        '', 'p.testy@sente.pl', null, true, current_timestamp),
       (41, 212, 19, 1, 1, 'p.ddddd@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp,
        'Administrator', '', 'p.ddddd@sente.pl', null, true, current_timestamp),
       (42, 213, 1, 3, 1, 'testhasla@sente.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'test',
        'hasla', 'testhasla@sente.pl', null, true, current_timestamp),
       (43, 214, 20, 1, 3, 'test1231@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Administrator',
        '', 'test1231@wp.pl', null, true, current_timestamp),
       (44, 215, 1, 3, 3, 'ptesty@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piter', 'Test',
        'ptesty@wp.pl', null, true, current_timestamp),
       (45, 216, 1, 3, 1, 'ptesty1@wp.pl', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Piter', 'Test',
        'ptesty1@wp.pl', null, true, current_timestamp),
       (46, 217, 1, 3, 3, 'test@example.com', '" . '$2y$13$BIs/mLTb/b6Cew1P0zK8/eVBafjLEg2Qn2CuWDK8Q.qrDeZ81R7/S' ."', current_timestamp, 'Test',
        'Testowy', 'test@example.com', null, true, current_timestamp);");

        $this->addSql("UPDATE \"user\" SET store_id = 1 WHERE id = 1;");

        $this->addSql("SELECT setval('user_id_seq', 46, true);");
    }

    public function down(Schema $schema): void
    {

    }
}
