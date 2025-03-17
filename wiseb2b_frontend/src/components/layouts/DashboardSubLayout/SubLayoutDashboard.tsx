// sublayout dashbordu - menu w sidebarze

import React, { FC, useCallback, useEffect } from 'react';
import { Outlet } from 'react-router';
import { useLocation } from 'react-router-dom';
import { Grid } from '@mui/material';
import classnames from 'classnames';
import each from 'lodash/each';
import find from 'lodash/find';

import { useSelector } from 'store';
import { useAppNavigate } from 'hooks';
import { Container, Breadcrumbs } from 'components/controls';
import { DashboardMenu } from 'components/containers';

import styles from 'theme/components/layouts/DashboardSubLayout/SubLayoutDashboard.module.scss';

// typ danych wejściowych
interface IProps {
  urlPrefix: string;
}

interface IRoute {
  label: string;
  path: string;
  key: string | null;
}

export const userRolesAccessRoutes = (isAdminPanel?: boolean) => ({
  ROLE_USER_MAIN: {
    orders: [
      { label: 'Zamówienia', path: '/dashboard/orders', key: 'orders' },
      { label: 'Dostawy', path: '/dashboard/deliveries', key: 'deliveries' }
    ],
    carts: [
      { label: 'Lista koszyków', path: '/dashboard/carts', key: 'carts' },
      { label: 'Importuj koszyk', path: '/dashboard/carts/import', key: 'import' },
      { label: 'Listy zakupowe', path: '/dashboard/shopping-lists', key: 'shopping-lists' }
    ],
    payments: [{ label: 'Lista płatności', path: '/dashboard/payments', key: 'payments' }],
    offers: [
      { label: 'Dedykowane', path: '/dashboard/offers/dedicated', key: 'dedicated' },
      { label: 'Okresowe', path: '/dashboard/offers/periodic', key: 'periodic' },
      { label: 'Export danych', path: '/dashboard/export', key: 'export' }
    ],
    documents: [{ label: 'Dokumenty', path: '/dashboard/documents', key: 'documents' }],
    myData: [
      { label: 'Moje konto', path: '/dashboard/my-account', key: 'my-account' },
      { label: 'Zgody', path: '/dashboard/consent', key: 'consent' },
      { label: 'Komunikaty', path: '/dashboard/message-settings', key: 'message-settings' },
      { label: 'Regulamin', path: '/dashboard/terms', key: 'terms' },
      { label: 'FAQ', path: '/dashboard/faq', key: 'faq' }
    ],
    clients: [],
    catalog: [],
    search: [],
    marketing: [],
    users: [],
    myCompany: [
      { label: 'Lista użytkowników', path: '/dashboard/users', key: 'users' },
      { label: 'Punkty odbioru', path: '/dashboard/receivers', key: 'receivers' }
    ],
    cms: [],
    gpsr: [],
    configuration: [],
    adminApi: [],
    system: []
  },
  ROLE_USER: {
    orders: [
      { label: 'Zamówienia', path: '/dashboard/orders', key: 'orders' },
      { label: 'Dostawy', path: '/dashboard/deliveries', key: 'deliveries' }
    ],
    carts: [
      { label: 'Lista koszyków', path: '/dashboard/carts', key: 'carts' },
      { label: 'Importuj koszyk', path: '/dashboard/carts/import', key: 'import' },
      { label: 'Listy zakupowe', path: '/dashboard/shopping-lists', key: 'shopping-lists' }
    ],
    payments: [{ label: 'Lista płatności', path: '/dashboard/payments', key: 'payments' }],
    offers: [
      { label: 'Dedykowane', path: '/dashboard/offers/dedicated', key: 'dedicated' },
      { label: 'Okresowe', path: '/dashboard/offers/periodic', key: 'periodic' },
      { label: 'Export danych', path: '/dashboard/export', key: 'export' }
    ],
    documents: [{ label: 'Dokumenty', path: '/dashboard/documents', key: 'documents' }],
    myData: [
      { label: 'Moje konto', path: '/dashboard/my-account', key: 'my-account' },
      { label: 'Zgody', path: '/dashboard/consent', key: 'consent' },
      { label: 'Komunikaty', path: '/dashboard/message-settings', key: 'message-settings' },
      { label: 'Regulamin', path: '/dashboard/terms', key: 'terms' },
      { label: 'FAQ', path: '/dashboard/faq', key: 'faq' }
    ],
    clients: [],
    catalog: [],
    search: [],
    marketing: [],
    users: [],
    myCompany: [],
    cms: [],
    gpsr: [],
    configuration: [],
    adminApi: [],
    system: []
  },
  ROLE_ADMIN: !isAdminPanel
    ? {
        orders: [
          { label: 'Zamówienia', path: '/dashboard/orders', key: 'orders' },
          { label: 'Dostawy', path: '/dashboard/deliveries', key: 'deliveries' }
        ],
        carts: [
          { label: 'Lista koszyków', path: '/dashboard/carts', key: 'carts' },
          { label: 'Importuj koszyk', path: '/dashboard/carts/import', key: 'import' },
          { label: 'Listy zakupowe', path: '/dashboard/shopping-lists', key: 'shopping-lists' }
        ],
        payments: [{ label: 'Lista płatności', path: '/dashboard/payments', key: 'payments' }],
        offers: [
          { label: 'Dedykowane', path: '/dashboard/offers/dedicated', key: 'dedicated' },
          { label: 'Okresowe', path: '/dashboard/offers/periodic', key: 'periodic' },
          { label: 'Export danych', path: '/dashboard/export', key: 'export' }
        ],
        documents: [{ label: 'Dokumenty', path: '/dashboard/documents', key: 'documents' }],
        myData: [
          { label: 'Moje konto', path: '/dashboard/my-account', key: 'my-account' },
          { label: 'Zgody', path: '/dashboard/consent', key: 'consent' },
          { label: 'Komunikaty', path: '/dashboard/message-settings', key: 'message-settings' },
          { label: 'Regulamin', path: '/dashboard/terms', key: 'terms' },
          { label: 'FAQ', path: '/dashboard/faq', key: 'faq' }
        ],
        clients: [
          { label: 'Lista klientów', path: '/dashboard/clients', key: 'clients' },
          { label: 'Adresy dostaw', path: '/dashboard/receivers', key: 'receivers' }
        ],
        catalog: [],
        search: [],
        marketing: [],
        users: [],
        myCompany: [],
        cms: [
          { label: 'Sekcje', path: '/dashboard/cms/sections', key: 'sections' },
          { label: 'Artykuły', path: '/dashboard/cms/articles', key: 'articles' },
          { label: 'Media', path: '/dashboard/cms/media', key: 'media' }
        ],
        gpsr: [],
        configuration: [],
        adminApi: [],
        system: []
      }
    : {
        orders: [
          {
            label: 'Zamówienia',
            path: '/managment_panel/dynamic_page/PANEL_ORDERS',
            key: 'managment_panel-orders'
          },
          {
            label: 'Dokumenty',
            path: '/managment_panel/dynamic_page/PANEL_DOCUMENTS',
            key: 'managment_panel-documents'
          },
          {
            label: 'Dostawy',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERIES',
            key: 'managment_panel-deliveries'
          },

          {
            label: 'Rozrachunki',
            path: '/managment_panel/dynamic_page/PANEL_SETTLEMENTS ',
            key: 'managment_panel-settlements'
          }
        ],
        carts: [],
        payments: [],
        offers: [],
        documents: [],
        myData: [],
        clients: [
          {
            label: 'Klienci',
            path: '/managment_panel/dynamic_page/clients_page',
            key: 'managment_panel-clients'
          },
          {
            label: 'Odbiorcy',
            path: '/managment_panel/dynamic_page/PANEL_RECEIVERS',
            key: 'managment_panel-receivers'
          }
        ],
        catalog: [
          {
            label: 'Produkty',
            path: '/managment_panel/dynamic_page/PANEL_PRODUCTS',
            key: 'managment_panel-products'
          },
          {
            label: 'Kategorie',
            path: '/managment_panel/dynamic_page/PANEL_CATEGORIES_LIST',
            key: 'managment_panel-categories'
          },
          {
            label: 'Usługi',
            path: '/managment_panel/dynamic_page/PANEL_SERVICES',
            key: 'managment_panel-services'
          }
        ],
        search: [
          {
            label: 'Wyszukiwarka produktów',
            path: '/managment_panel/dynamic_page/PANEL_PRODUCT_CATALOGS_LIST',
            key: 'product-search'
          }
        ],
        marketing: [
          {
            label: 'Promocje katalogowe',
            path: '/managment_panel/dynamic_page/PANEL_PROMOTION_LIST',
            key: 'managment_panel-promotions'
          },
          {
            label: 'Koszty dostawy i płatności',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERY_PAYMENT_COST_LIST',
            key: 'payments'
          }
        ],
        users: [],
        myCompany: [],
        cms: [
          {
            label: 'Artykuły',
            path: '/managment_panel/cms/articles',
            key: 'managment_panel-articles'
          },
          { label: 'Media', path: '/managment_panel/cms/media', key: 'managment_panel-media' }
        ],
        gpsr: [
          {
            label: 'Dostawcy GPSR',
            path: '/managment_panel/dynamic_page/PANEL_SUPPLIERS_LIST',
            key: 'managment_panel-suppliers-list'
          }
        ],
        configuration: [
          {
            label: 'Metody płatności',
            path: '/managment_panel/dynamic_page/PANEL_PAYMENT_METHODS',
            key: 'managment_panel-payment-methods'
          },
          {
            label: 'Metody dostawy',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERY_METHODS',
            key: 'managment_panel-delivery-methods'
          },
          {
            label: 'Sekcje CMS',
            path: '/managment_panel/cms/sections',
            key: 'managment_panel-sections'
          },
          {
            label: 'Umowy/Zgody',
            path: '/managment_panel/dynamic_page/PANEL_CONTRACT_LIST',
            key: 'managment_panel-contracts'
          }
        ],
        adminApi: [
          {
            label: 'Dziennik wymiany',
            path: '/managment_panel/dynamic_page/PANEL_REPLICATION_LOG',
            key: 'admin-api-replication'
          },
          {
            label: 'AdminAPI - błędne obiekty',
            path: '/managment_panel/dynamic_page/PANEL_REPLICATION_OBJECT_FAILED',
            key: 'admin-api-replication-failed'
          },
          {
            label: 'Statystyki wymiany',
            path: '/managment_panel/dynamic_page/PANEL_LOG_STATISTICS ',
            key: 'managment_panel-stats'
          }
        ],
        system: [
          {
            label: 'Lista użytkowników',
            path: '/managment_panel/dynamic_page/PANEL_USERS',
            key: 'managment_panel-users'
          }
        ]
      },
  ROLE_TRADER: !isAdminPanel
    ? {
        orders: [
          { label: 'Zamówienia', path: '/dashboard/orders', key: 'orders' },
          { label: 'Dostawy', path: '/dashboard/deliveries', key: 'deliveries' }
        ],
        carts: [
          { label: 'Lista koszyków', path: '/dashboard/carts', key: 'carts' },
          { label: 'Importuj koszyk', path: '/dashboard/carts/import', key: 'import' },
          { label: 'Listy zakupowe', path: '/dashboard/shopping-lists', key: 'shopping-lists' }
        ],
        payments: [{ label: 'Lista płatności', path: '/dashboard/payments', key: 'payments' }],
        offers: [
          { label: 'Dedykowane', path: '/dashboard/offers/dedicated', key: 'dedicated' },
          { label: 'Okresowe', path: '/dashboard/offers/periodic', key: 'periodic' },
          { label: 'Export danych', path: '/dashboard/export', key: 'export' }
        ],
        documents: [{ label: 'Dokumenty', path: '/dashboard/documents', key: 'documents' }],
        myData: [
          { label: 'Moje konto', path: '/dashboard/my-account', key: 'my-account' },
          { label: 'Zgody', path: '/dashboard/consent', key: 'consent' },
          { label: 'Komunikaty', path: '/dashboard/message-settings', key: 'message-settings' },
          { label: 'Regulamin', path: '/dashboard/terms', key: 'terms' },
          { label: 'FAQ', path: '/dashboard/faq', key: 'faq' }
        ],
        clients: [
          { label: 'Lista klientów', path: '/dashboard/clients', key: 'clients' },
          { label: 'Adresy dostaw', path: '/dashboard/receivers', key: 'receivers' }
        ],
        catalog: [],
        search: [],
        marketing: [],
        users: [],
        myCompany: [],
        cms: [],
        gpsr: [],
        configuration: [],
        adminApi: [],
        system: []
      }
    : {
        orders: [
          {
            label: 'Zamówienia',
            path: '/managment_panel/dynamic_page/PANEL_ORDERS',
            key: 'managment_panel-orders'
          },
          {
            label: 'Dokumenty',
            path: '/managment_panel/dynamic_page/PANEL_DOCUMENTS',
            key: 'managment_panel-documents'
          },
          {
            label: 'Dostawy',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERIES',
            key: 'managment_panel-deliveries'
          },

          {
            label: 'Rozrachunki',
            path: '/managment_panel/dynamic_page/PANEL_SETTLEMENTS ',
            key: 'managment_panel-settlements'
          }
        ],
        carts: [],
        payments: [],
        offers: [],
        documents: [],
        myData: [],
        clients: [],
        catalog: [
          {
            label: 'Produkty',
            path: '/managment_panel/dynamic_page/products',
            key: 'managment_panel-products'
          },
          {
            label: 'Kategorie',
            path: '/managment_panel/dynamic_page/categories',
            key: 'managment_panel-categories'
          },
          {
            label: 'Koszty dostawy i płatności',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERY_PAYMENT_COST_LIST',
            key: 'payments'
          },
          {
            label: 'Usługi',
            path: '/managment_panel/dynamic_page/PANEL_SERVICES',
            key: 'managment_panel-services'
          }
        ],
        search: [
          {
            label: 'Wyszukiwarka produktów',
            path: '/managment_panel/dynamic_page/PANEL_PRODUCT_CATALOGS_LIST',
            key: 'product-search'
          }
        ],
        marketing: [
          {
            label: 'Promocje katalogowe',
            path: '/managment_panel/dynamic_page/PANEL_PROMOTION_LIST',
            key: 'managment_panel-promotions'
          },
          {
            label: 'Koszty dostawy i płatności',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERY_PAYMENT_COST_LIST',
            key: 'payments'
          }
        ],
        users: [],
        myCompany: [],
        cms: [],
        gpsr: [
          {
            label: 'Dostawcy GPSR',
            path: '/managment_panel/dynamic_page/PANEL_SUPPLIERS_LIST',
            key: 'managment_panel-suppliers-list'
          }
        ],
        configuration: [
          {
            label: 'Metody płatności',
            path: '/managment_panel/dynamic_page/PANEL_PAYMENT_METHODS',
            key: 'managment_panel-payment-methods'
          },
          {
            label: 'Metody dostawy',
            path: '/managment_panel/dynamic_page/PANEL_DELIVERY_METHODS',
            key: 'managment_panel-delivery-methods'
          },
          {
            label: 'Umowy/Zgody',
            path: '/managment_panel/dynamic_page/PANEL_CONTRACT_LIST',
            key: 'managment_panel-contracts'
          }
        ],
        adminApi: [
          {
            label: 'Dziennik wymiany',
            path: '/managment_panel/dynamic_page/PANEL_REPLICATION_LOG',
            key: 'admin-api-replication'
          },
          {
            label: 'AdminAPI - błędne obiekty',
            path: '/managment_panel/dynamic_page/PANEL_REPLICATION_OBJECT_FAILED',
            key: 'admin-api-replication-failed'
          },
          {
            label: 'Statystyki wymiany',
            path: '/managment_panel/dynamic_page/PANEL_LOG_STATISTICS ',
            key: 'managment_panel-stats'
          }
        ],
        system: [
          {
            label: 'Statystyki',
            path: '/managment_panel/dynamic_page/PANEL_LOG_STATISTICS ',
            key: 'managment_panel-stats'
          }
        ]
      },
  ROLE_OPEN_PROFILE: {
    orders: [],
    carts: [],
    payments: [],
    offers: [],
    documents: [],
    myData: [],
    clients: [],
    catalog: [],
    search: [],
    marketing: [],
    users: [],
    myCompany: [],
    cms: [],
    gpsr: [],
    configuration: [],
    adminApi: [],
    system: []
  }
});

const SubLayoutDashboard: FC<IProps> = ({ urlPrefix }) => {
  const { profile } = useSelector((state) => state.auth);
  const navigate = useAppNavigate();

  // ścieżka z url
  const { pathname } = useLocation();

  // czy użytkownik ma dostęp do podstrony
  const allowRoute = useCallback(() => {
    let arr: IRoute[] = [{ label: 'Dashboard', path: '/dashboard', key: null }];

    each(
      userRolesAccessRoutes()[`${profile?.role || 'ROLE_OPEN_PROFILE'}`],
      (route) => (arr = [...arr, ...route])
    );

    return !!find(arr, (route) => route.key && pathname.includes(route.key));
  }, [pathname]);

  useEffect(() => {
    if (pathname === `${urlPrefix}/dashboard`) {
      return;
    }

    if (!allowRoute()) {
      navigate('/dashboard');
    }
  }, [pathname]);

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-DashboardSubLayout'
      )}>
      <Container>
        <Breadcrumbs />
        <Grid container columnSpacing="32px">
          <Grid item md={2} xs={12} className={styles.menuWrapper}>
            {profile?.role && (
              <DashboardMenu accessRoutes={userRolesAccessRoutes()[`${profile.role}`]} />
            )}
          </Grid>
          <Grid item md={10} xs={12}>
            <Outlet />
          </Grid>
        </Grid>
      </Container>
    </div>
  );
};

export default SubLayoutDashboard;
