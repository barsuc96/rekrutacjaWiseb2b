<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230222162804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migracja danych z Wise 1.5: odbiorcy, adresy odbiorców i klientów, koszyki, pozycje koszyków i zamówienia wzorowane na 10 koszykach o statusie 1';
    }

    public function up(Schema $schema): void
    {
        // receiver
        $this->addSql("delete from receiver;");
        $this->addSql("insert into receiver (id, id_external, client_id, name, first_name, last_name, email, phone, is_default, is_active, sys_insert_date, dtype)
values  (1, '13', 1, 'Quattro Forum', 'Jan', 'Nowak', 'biuro@sente.pl', '700700700', true, true, current_timestamp, 'receiver'),
        (2, '15', 2, 'Siedziba Główna', 'Jan','Nowak', 'nowak@example.com', '123456789', true, true, current_timestamp, 'receiver'),
        (3, '16', 1, 'Punkt Obsługi Klienta', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (4, '17', 1, 'Dział Księgowości', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (5, '18', 1, 'Magazyn', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (6, '19', 1, 'Serwis', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (7, '20', 1, 'Biuro Regionalne', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (8, '21', 1, 'Dział IT', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (9, '22', 1, 'Dział Marketingu', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (10, '23', 1, 'Dział HR', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (11, '24', 1, 'Sklep Firmowy', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (12, '25', 1, 'Dział Sprzedaży', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (13, '26', 1, 'Dział Logistyki', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (14, '27', 1, 'Centrum Szkoleniowe', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (15, '28', 1, 'Dział Produkcji', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (16, '29', 1, 'Dział R&D', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (17, '30', 1, 'Krakowska - siedziba', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (18, '31', 1, 'Centrum Serwisowe', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (19, '32', 1, 'Sklep Online', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (20, '33', 1, 'Dział Obsługi Reklamacji', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (21, '34', 1, 'Dział Analiz', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (22, '35', 1, 'Dział PR', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (23, '36', 1, 'Kierownik siedziba - Warszawa', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (24, '37', 1, 'Kierownik siedziba - Kraków', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (25, '38', 1, 'Kierownik siedziba - Ziąbkowice', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (26, '39', 1, 'Kierownik siedziba - Przemyśl', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (27, '40', 1, 'Kierownik siedziba - Tomaszówek', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (28, '41', 1, 'Kierownik siedziba - Opoczno', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (29, '42', 1, 'Kierownik siedziba - Drzewica', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver'),
        (30, '43', 1, 'Kierownik siedziba - Spała', 'Jan','Nowak', 'nowak@example.com', '123456789', false, true, current_timestamp, 'receiver');");
        $this->addSql("SELECT setval('receiver_id_seq', 30, true);");

        // global_address
        $this->addSql("delete from global_address;");
        $this->addSql("insert into global_address (id, entity_name, field_name, entity_id, name, street, house_number, apartment_number, city, postal_code, country_code, state, is_active, sys_insert_date)
values  (1, 'receiver', 'deliveryAddress', 1, 'Quattro Forum', 'Legnicka', '65', null, 'Wroclaw', '54-203', 'pl', null, true, current_timestamp),
        (2, 'receiver', 'deliveryAddress', 2, 'Siedziba Główna', 'Lipowa', '12', null, 'Wroclaw', '54-203', 'pl', null, true, current_timestamp),
        (3, 'receiver', 'deliveryAddress', 3, 'Punkt Obsługi Klienta', 'Firmowa', '43', null, 'Wrocław', '58-998', 'pl', null, true, current_timestamp),
        (4, 'receiver', 'deliveryAddress', 4, 'Dział Księgowości', 'ulica', '12', null, 'Lublin', '20-500', 'pl', null, true, current_timestamp),
        (5, 'receiver', 'deliveryAddress', 5, 'Magazyn', 'Słoneczna', '8', null, 'Lublin', '20-122', 'pl', null, true, current_timestamp),
        (6, 'receiver', 'deliveryAddress', 6, 'Serwis', 'Kwiatowa', '3', '54', 'Warszawa', '12-234', 'pl', null, true, current_timestamp),
        (7, 'receiver', 'deliveryAddress', 7, 'Biuro Regionalne', 'Jesionowa', '21', '43', 'Zielona Góra', '43-322', 'pl', null, true, current_timestamp),
        (8, 'receiver', 'deliveryAddress', 8, 'Dział IT', 'Wrocławska', '100', null, 'Wrocław', '53-031', 'pl', null, true, current_timestamp),
        (9, 'receiver', 'deliveryAddress', 9, 'Dział Marketingu', 'Wrocławska', '100', null, 'Świebodzice', '58-160', 'pl', null, true, current_timestamp),
        (10, 'receiver', 'deliveryAddress', 10, 'Dział HR', 'Wrocławska', '100', null, 'Wrocław', '58-150', 'pl', null, true, current_timestamp),
        (11, 'receiver', 'deliveryAddress', 11, 'Sklep Firmowy', 'Topolowa', '34', null, 'Tomsazów Mazowiecki', '43-322', 'pl', null, true, current_timestamp),
        (12, 'receiver', 'deliveryAddress', 12, 'Dział Sprzedaży', 'Kasztanowa', '76', null, 'Borowice', '43-111', 'pl', null, true, current_timestamp),
        (13, 'receiver', 'deliveryAddress', 13, 'Dział Logistyki', 'Grabowa', '7', null, 'Kraków', '11-211', 'pl', null, true, current_timestamp),
        (14, 'receiver', 'deliveryAddress', 14, 'Centrum Szkoleniowe', 'Grabowa', '7', null, 'Międzyrzecze', '43-766', 'pl', null, true, current_timestamp),
        (15, 'receiver', 'deliveryAddress', 15, 'Dział Produkcji', 'Grabowa', '7', null, 'Świebodzice', '58-160', 'pl', null, true, current_timestamp),
        (16, 'receiver', 'deliveryAddress', 16, 'Dział R&D', 'Jodłowa', '43', null, 'Kraków', '43-342', 'pl', null, true, current_timestamp),
        (17, 'receiver', 'deliveryAddress', 17, 'Krakowska - siedziba', 'Krakowska', '54', null, 'Moszczenica', '54-322', 'pl', null, true, current_timestamp),
        (18, 'receiver', 'deliveryAddress', 18, 'Centrum Serwisowe', 'Marcowa', '54', null, 'Opole', '43-111', 'pl', null, true, current_timestamp),
        (19, 'receiver', 'deliveryAddress', 19, 'Sklep Online', 'Olchowa', '25', null, 'Zielona Góra', '12-352', 'pl', null, true, current_timestamp),
        (20, 'receiver', 'deliveryAddress', 20, 'Dział Obsługi Reklamacji', 'Testowa', '1', null, 'Wrocław', '11-22', 'pl', null, true, current_timestamp),
        (21, 'receiver', 'deliveryAddress', 21, 'Dział Analiz', 'Wrocławska', '1', null, 'Wrocław', '11-222', 'pl', null, true, current_timestamp),
        (22, 'receiver', 'deliveryAddress', 22, 'Dział PR', 'Legnicka', '21', null, 'Wrocław', '63-456', 'pl', null, true, current_timestamp),
        (23, 'receiver', 'deliveryAddress', 23, 'Kierownik siedziba - Warszawa', 'Legnicka', '21', null, 'Wrocław', '63-456', 'pl', null, true, current_timestamp),
        (24, 'receiver', 'deliveryAddress', 24, 'Kierownik siedziba - Kraków', 'Legnicka', '21', null, 'Kraków', '63-456', 'pl', null, true, current_timestamp),
        (25, 'receiver', 'deliveryAddress', 25, 'Kierownik siedziba - Ziąbkowice', 'Legnicka', '21', null, 'Ziąbkowice', '63-456', 'pl', null, true, current_timestamp),
        (26, 'receiver', 'deliveryAddress', 26, 'Kierownik siedziba - Przemyśl', 'Legnicka', '21', null, 'Przemyśl', '63-456', 'pl', null, true, current_timestamp),
        (27, 'receiver', 'deliveryAddress', 27, 'Kierownik siedziba - Tomaszówek', 'Legnicka', '21', null, 'Tomaszówek', '63-456', 'pl', null, true, current_timestamp),
        (28, 'receiver', 'deliveryAddress', 28, 'Kierownik siedziba - Opoczno', 'Legnicka', '21', null, 'Opoczno', '63-456', 'pl', null, true, current_timestamp),
        (29, 'receiver', 'deliveryAddress', 29, 'Kierownik siedziba - Drzewica', 'Legnicka', '21', null, 'Drzewica', '63-456', 'pl', null, true, current_timestamp),
        (30, 'receiver', 'deliveryAddress', 30, 'Kierownik siedziba - Spała', 'Legnicka', '21', null, 'Spała', '63-456', 'pl', null, true, current_timestamp),
        (31, 'client', 'registerAddress', 1, 'Sente SENTE Informatyczne Address', 'Legnicka', '51-53', '4', 'Wrocław', '54-203', 'pl', null, null, current_timestamp),
        (32, 'client', 'registerAddress', 2, 'Test Company Address', 'Testowa', '43', 'Wroclaw', '22-100', 'pl', null, null, null, current_timestamp),
        (33, 'client', 'registerAddress', 3, 'Ernest Address', 'Tabalugowa', '2', 'Zlotoryja', '57-099', 'pl', null, null, null, current_timestamp),
        (34, 'client', 'registerAddress', 4, 'KYOTU Address', 'STREET', '43', 'WRO', '11-111', 'pl', null, null, null, current_timestamp),
        (35, 'client', 'registerAddress', 5, 'DK company Address', 'DK', '65', 'Lublin', '20-500', 'pl', null, null, null, current_timestamp),
        (36, 'client', 'registerAddress', 6, 'Przykladowa 1 Address', 'Lesna', '77', 'Wroclaw', '58-100', 'pl', null, null, null, current_timestamp),
        (37, 'client', 'registerAddress', 7, 'string Address', 'string', '32', 'string', 'string', 'pl', null, null, null, current_timestamp),
        (38, 'client', 'registerAddress', 8, '555 Address', 'string', '65', 'string', 'string', 'pl', null, null, null, current_timestamp),
        (39, 'client', 'registerAddress', 9, 'Klient testowy z API Address', 'Wrocławska', '100', 'Wrocław', '11-222', 'pl', null, null, null, current_timestamp),
        (40, 'client', 'registerAddress', 10, 'Klient testowy z API Address', 'Wrocławska', '100', 'Wrocław', '11-222', 'pl', null, null, null, current_timestamp),
        (41, 'client', 'registerAddress', 11, 'Klient testowy z API_1 Address', 'Wrocławska', '100', 'Wrocław', '11-222', 'pl', null, null, null, current_timestamp),
        (42, 'client', 'registerAddress', 12, 'Klient_1 Address', 'Wrocławska', '54', 'Wroclaw', '11-022', 'CZ', null, null, null, current_timestamp),
        (43, 'client', 'registerAddress', 13, 'Klient_1 Address', 'Wrocławska', '76', 'Wroclaw', '11-022', 'pl', null, null, null, current_timestamp),
        (44, 'client', 'registerAddress', 14, 'Klient_1 Address', 'Wrocławska', '2', 'Wroclaw', '11-022', 'pl', null, null, null, current_timestamp),
        (45, 'client', 'registerAddress', 15, 'Piotr Testowy z API Address', 'Testowa', '100', 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp),
        (46, 'client', 'registerAddress', 16, 'Firma testowa PF_Test Address', 'Testowa', null, 'Świebodzice', '58-160', 'pl', null, null, null, current_timestamp),
        (47, 'client', 'registerAddress', 17, 'asdfasdf Address', 'asdfsadf', null, 'dfgdfgdfg', 'dfgdfg', 'CZ', null, null, null, current_timestamp),
        (48, 'client', 'registerAddress', 18, 'Firma testing Address', 'Wrocławska', null, 'Wrocław', '11-222', 'pl', null, null, null, current_timestamp),
        (49, 'client', 'registerAddress', 19, 'NazwaFirmy Address', 'Testowa', '1', 'Wrocław', '11-222', 'pl', null, null, null, current_timestamp),
        (50, 'client', 'registerAddress', 20, 'ZmianaNazyw klient nowy Address', 'Ulica', null, 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp),
        (51, 'order', 'clientAddress', 61, 'Adres klienta', 'Ulica', null, 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp),
        (52, 'order', 'receiverAddress', 61, 'Adres klienta', 'Ulica', null, 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp),
        (53, 'order', 'clientAddress', 1, 'Adres klienta', 'Ulica', null, 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp),
        (54, 'order', 'receiverAddress', 1, 'Adres odbiorcy', 'Ulica', null, 'Wroclaw', '11-222', 'pl', null, null, null, current_timestamp);");
        $this->addSql("SELECT setval('global_address_id_seq', 54, true);");
    }

    public function down(Schema $schema): void
    {

    }
}
