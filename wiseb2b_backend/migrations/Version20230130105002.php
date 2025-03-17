<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230130105002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Ustawienie domyślnych wartości dla tabel api_scope i api_client_scope';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("
                insert into public.api_scope (id, name, created_at, updated_at)
                values  (1, 'GENERAL_ACCESS', '2022-08-17 09:30:43', null),
                        (2, 'GENERAL_PUT', '2022-08-17 09:30:43', null),
                        (3, 'GENERAL_GET', '2022-08-17 09:30:43', null),
                        (4, 'GENERAL_DELETE', '2022-08-17 09:30:43', null),
                        (5, 'AGREEMENTS_ALL', '2022-08-17 09:28:58', null),
                        (6, 'AGREEMENTS_PUT', '2022-08-17 09:28:58', null),
                        (7, 'AGREEMENTS_GET', '2022-08-17 09:28:58', null),
                        (8, 'AGREEMENTS_DELETE', '2022-08-17 09:28:58', null),
                        (9, 'CATEGORIES_ALL', '2022-08-17 09:29:03', null),
                        (10, 'CATEGORIES_PUT', '2022-08-17 09:29:03', null),
                        (11, 'CATEGORIES_GET', '2022-08-17 09:29:03', null),
                        (12, 'CATEGORIES_DELETE', '2022-08-17 09:29:03', null),
                        (13, 'CLIENTS_ALL', '2022-08-17 09:29:08', null),
                        (14, 'CLIENTS_PUT', '2022-08-17 09:29:08', null),
                        (15, 'CLIENTS_GET', '2022-08-17 09:29:08', null),
                        (16, 'CLIENTS_DELETE', '2022-08-17 09:29:08', null),
                        (17, 'COUNTRIES_ALL', '2022-08-17 09:29:12', null),
                        (18, 'COUNTRIES_PUT', '2022-08-17 09:29:12', null),
                        (19, 'COUNTRIES_GET', '2022-08-17 09:29:12', null),
                        (20, 'COUNTRIES_DELETE', '2022-08-17 09:29:12', null),
                        (21, 'DELIVERY_METHODS_ALL', '2022-08-17 09:29:17', null),
                        (22, 'DELIVERY_METHODS_PUT', '2022-08-17 09:29:17', null),
                        (23, 'DELIVERY_METHODS_GET', '2022-08-17 09:29:17', null),
                        (24, 'DELIVERY_METHODS_DELETE', '2022-08-17 09:29:17', null),
                        (25, 'DOCUMENTS_ALL', '2022-08-17 09:29:22', null),
                        (26, 'DOCUMENTS_PUT', '2022-08-17 09:29:22', null),
                        (27, 'DOCUMENTS_GET', '2022-08-17 09:29:22', null),
                        (28, 'DOCUMENTS_DELETE', '2022-08-17 09:29:22', null),
                        (29, 'INVENTORIES_ALL', '2022-08-17 09:29:27', null),
                        (30, 'INVENTORIES_PUT', '2022-08-17 09:29:27', null),
                        (31, 'INVENTORIES_GET', '2022-08-17 09:29:27', null),
                        (32, 'INVENTORIES_DELETE', '2022-08-17 09:29:27', null),
                        (33, 'ORDERS_ALL', '2022-08-17 09:29:34', null),
                        (34, 'ORDERS_PUT', '2022-08-17 09:29:34', null),
                        (35, 'ORDERS_GET', '2022-08-17 09:29:34', null),
                        (36, 'ORDERS_DELETE', '2022-08-17 09:29:34', null),
                        (37, 'ORDERS_STATUS_ALL', '2022-08-17 09:29:38', null),
                        (38, 'ORDERS_STATUS_PUT', '2022-08-17 09:29:38', null),
                        (39, 'ORDERS_STATUS_GET', '2022-08-17 09:29:38', null),
                        (40, 'ORDERS_STATUS_DELETE', '2022-08-17 09:29:38', null),
                        (41, 'PAYMENT_METHODS_ALL', '2022-08-17 09:29:42', null),
                        (42, 'PAYMENT_METHODS_PUT', '2022-08-17 09:29:42', null),
                        (43, 'PAYMENT_METHODS_GET', '2022-08-17 09:29:42', null),
                        (44, 'PAYMENT_METHODS_DELETE', '2022-08-17 09:29:42', null),
                        (45, 'PRICE_GROUPS_ALL', '2022-08-17 09:29:47', null),
                        (46, 'PRICE_GROUPS_PUT', '2022-08-17 09:29:47', null),
                        (47, 'PRICE_GROUPS_GET', '2022-08-17 09:29:47', null),
                        (48, 'PRICE_GROUPS_DELETE', '2022-08-17 09:29:47', null),
                        (49, 'PRODUCTS_ALL', '2022-08-17 09:29:52', null),
                        (50, 'PRODUCTS_PUT', '2022-08-17 09:29:52', null),
                        (51, 'PRODUCTS_GET', '2022-08-17 09:29:52', null),
                        (52, 'PRODUCTS_DELETE', '2022-08-17 09:29:52', null),
                        (53, 'PRODUCTS_ATTRIBUTES_ALL', '2022-08-17 09:29:55', null),
                        (54, 'PRODUCTS_ATTRIBUTES_PUT', '2022-08-17 09:29:55', null),
                        (55, 'PRODUCTS_ATTRIBUTES_GET', '2022-08-17 09:29:55', null),
                        (56, 'PRODUCTS_ATTRIBUTES_DELETE', '2022-08-17 09:29:55', null),
                        (57, 'PRODUCTS_IMAGES_ALL', '2022-08-17 09:29:59', null),
                        (58, 'PRODUCTS_IMAGES_PUT', '2022-08-17 09:29:59', null),
                        (59, 'PRODUCTS_IMAGES_GET', '2022-08-17 09:29:59', null),
                        (60, 'PRODUCTS_IMAGES_DELETE', '2022-08-17 09:29:59', null),
                        (61, 'PROMOTIONS_ALL', '2022-08-17 09:30:04', null),
                        (62, 'PROMOTIONS_PUT', '2022-08-17 09:30:04', null),
                        (63, 'PROMOTIONS_GET', '2022-08-17 09:30:04', null),
                        (64, 'PROMOTIONS_DELETE', '2022-08-17 09:30:04', null),
                        (65, 'UNITS_ALL', '2022-08-17 09:30:08', null),
                        (66, 'UNITS_PUT', '2022-08-17 09:30:08', null),
                        (67, 'UNITS_GET', '2022-08-17 09:30:08', null),
                        (68, 'UNITS_DELETE', '2022-08-17 09:30:08', null),
                        (69, 'WAREHOUSES_ALL', '2022-08-17 09:30:14', null),
                        (70, 'WAREHOUSES_PUT', '2022-08-17 09:30:14', null),
                        (71, 'WAREHOUSES_GET', '2022-08-17 09:30:14', null),
                        (72, 'WAREHOUSES_DELETE', '2022-08-17 09:30:14', null),
                        (73, 'AGREEMENTS_TYPES_PUT', '2022-09-28 10:00:00', null),
                        (74, 'ORDERS_STATUS_PUT', '2022-09-28 10:00:00', null),
                        (75, 'PRODUCTS_PRICES_ALL', '2022-09-28 10:00:00', null),
                        (76, 'PRODUCTS_PRICES_PUT', '2022-09-28 10:00:00', null),
                        (77, 'PRODUCTS_PRICES_GET', '2022-09-28 10:00:00', null),
                        (78, 'PRODUCTS_PRICES_DELETE', '2022-09-28 10:00:00', null),
                        (79, 'RECEIVERS_ALL', '2022-09-28 10:00:00', null),
                        (80, 'RECEIVERS_PUT', '2022-09-28 10:00:00', null),
                        (81, 'RECEIVERS_GET', '2022-09-28 10:00:00', null),
                        (82, 'RECEIVERS_DELETE', '2022-09-28 10:00:00', null),
                        (83, 'STOCKS_ALL', '2022-09-28 10:00:00', null),
                        (84, 'STOCKS_PUT', '2022-09-28 10:00:00', null),
                        (85, 'STOCKS_GET', '2022-09-28 10:00:00', null),
                        (86, 'STOCKS_DELETE', '2022-09-28 10:00:00', null),
                        (87, 'PRODUCTS_UNITS_ALL', '2022-09-28 10:00:00', null),
                        (88, 'PRODUCTS_UNITS_PUT', '2022-09-28 10:00:00', null),
                        (89, 'PRODUCTS_UNITS_GET', '2022-09-28 10:00:00', null),
                        (90, 'PRODUCTS_UNITS_DELETE', '2022-09-28 10:00:00', null),
                        (91, 'USERS_ALL', '2022-09-28 10:00:00', null),
                        (92, 'USERS_PUT', '2022-09-28 10:00:00', null),
                        (93, 'USERS_GET', '2022-09-28 10:00:00', null),
                        (94, 'USERS_DELETE', '2022-09-28 10:00:00', null);
        ");

        $this->addSql("INSERT INTO public.api_client_scope (api_client_id, api_scope_id) VALUES ('dea6d5hagv0c342d674o087Ef3e13E7g', 1);");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM public.api_scope WHERE id < 95;");
        $this->addSql("DELETE FROM public.api_client_scope WHERE api_client_id = 'dea6d5hagv0c342d674o087Ef3e13E7g';");
    }
}
