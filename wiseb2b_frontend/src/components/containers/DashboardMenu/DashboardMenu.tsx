import React, { useCallback, useEffect, useState, FC, ReactElement } from 'react';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';
import { useTranslation } from 'react-i18next';
import {
  Cart,
  ChevronDown,
  CreditCard,
  JournalText,
  Person,
  PersonBadge,
  Gear,
  Megaphone,
  Building,
  Book,
  People,
  Search
} from 'react-bootstrap-icons';

import { reduxActions, useDispatch } from 'store';
import { Link } from 'components/controls';

import { ClipboardIcon } from 'assets/icons';
import styles from 'theme/components/containers/DashboardMenu/DashboardMenu.module.scss';

interface IRoute {
  label: string;
  path: string;
}

interface IAccessRoutes {
  orders: IRoute[];
  carts: IRoute[];
  payments: IRoute[];
  offers: IRoute[];
  myData: IRoute[];
  clients: IRoute[];
  catalog: IRoute[];
  search: IRoute[];
  marketing: IRoute[];
  users: IRoute[];
  documents: IRoute[];
  myCompany: IRoute[];
  cms: IRoute[];
  gpsr: IRoute[];
  configuration: IRoute[];
  adminApi: IRoute[];
  system: IRoute[];
}

interface IMenuItem {
  label: string;
  icon?: ReactElement;
  items?: IRoute[];
  path?: string;
}

// typ danych wejściowych
interface IProps {
  isAdminPanel?: boolean;
  accessRoutes: IAccessRoutes;
}

const DashboardMenu: FC<IProps> = ({ isAdminPanel, accessRoutes }) => {
  const dispatch = useDispatch();
  const { t } = useTranslation();

  // ścieżka z url
  const { pathname } = useLocation();

  // lista (indeksów tablicy) rozwiniętych bloków w menu
  const [openedParentItemIndexes, setOpenedParentItemIndexes] = useState<number[]>([]);

  // config menu
  const menuItems = isAdminPanel
    ? [
        {
          label: 'Dashboard',
          path: '/managment_panel'
        },
        {
          label: 'Sprzedaż',
          icon: <JournalText />,
          items: accessRoutes.orders
        },
        {
          label: 'Klienci',
          icon: <People />,
          items: accessRoutes.clients
        },
        {
          label: 'Katalog',
          icon: <Book />,
          items: accessRoutes.catalog
        },
        {
          label: 'Wyszukiwarka',
          icon: <Search />,
          items: accessRoutes.search
        },
        {
          label: 'Oferta',
          icon: <Megaphone />,
          items: accessRoutes.marketing
        },
        {
          label: 'CMS',
          icon: <Gear />,
          items: accessRoutes.cms
        },
        {
          label: 'GPSR',
          icon: <Gear />,
          items: accessRoutes.gpsr
        },
        {
          label: 'Konfiguracja',
          icon: <Gear />,
          items: accessRoutes.configuration
        },
        {
          label: 'Admin API',
          icon: <Gear />,
          items: accessRoutes.adminApi
        },
        {
          label: 'System',
          icon: <Gear />,
          items: accessRoutes.system
        }
      ]
    : [
        {
          label: 'Dashboard',
          path: '/dashboard'
        },
        {
          label: 'Zamówienia',
          icon: <JournalText />,
          items: accessRoutes.orders
        },
        {
          label: 'Koszyki',
          icon: <Cart />,
          items: accessRoutes.carts
        },
        {
          label: 'Płatności',
          icon: <CreditCard />,
          items: accessRoutes.payments
        },
        {
          label: 'Oferty',
          icon: <ClipboardIcon />,
          items: accessRoutes.offers
        },
        {
          label: 'Dokumenty',
          icon: <Book />,
          items: accessRoutes.documents
        },
        {
          label: 'Klienci',
          icon: <PersonBadge />,
          items: accessRoutes.clients
        },
        {
          label: 'Użytkownicy',
          icon: <Person />,
          items: accessRoutes.users
        },
        {
          label: 'Moje dane',
          icon: <Person />,
          items: accessRoutes.myData
        },
        {
          label: 'Moja firma',
          icon: <Building />,
          items: accessRoutes.myCompany
        }
      ];

  useEffect(() => {
    const blockItemIndex = menuItems.findIndex((menuItem) =>
      menuItem.items?.some((subItem) => isCurrentPath(subItem.path))
    );
    setOpenedParentItemIndexes((prevState) => [...prevState, blockItemIndex]);
  }, [pathname]);

  // funkcja testujacy czy podana ścieżka jest aktualną stroną
  const isCurrentPath = useCallback(
    (testedPath?: string) =>
      testedPath ? new RegExp(`^/[^\\/]+${testedPath}$`).test(pathname) : false,
    [pathname]
  );

  // rozwinięcie/zwinięcie bloku w mwnu
  const toggleItem = (index: number) => {
    setOpenedParentItemIndexes((prevState) =>
      openedParentItemIndexes.includes(index)
        ? prevState.filter((item) => item !== index)
        : [...prevState, index]
    );
  };

  const renderParentItems = (parentItem: IMenuItem, index: number) => {
    if (parentItem?.items?.length === 0) {
      return null;
    }

    return (
      <div className={styles.menuItem} key={index}>
        {!parentItem.items && parentItem.path ? (
          <Link
            to={parentItem.path}
            onClick={() => dispatch(reduxActions.setIsMobileMenu(false))}
            className={classnames(styles.link, {
              [styles.active]: isCurrentPath(parentItem.path)
            })}>
            <>
              <div className={styles.iconWrapper}>{parentItem.icon}</div>
              {t(parentItem.label)}
            </>
          </Link>
        ) : (
          <div
            className={classnames(styles.label, {
              [styles.open]: openedParentItemIndexes.includes(index)
            })}
            onClick={() => toggleItem(index)}>
            <div className={styles.iconWrapper}>{parentItem.icon}</div>
            {t(parentItem.label)}
            <ChevronDown className={styles.arrow} />
          </div>
        )}

        {openedParentItemIndexes.includes(index) && (
          <div className={styles.subItemsWrapper}>
            {(parentItem.items || []).map((subItem, subItemIndex) => (
              <Link
                key={subItemIndex}
                to={subItem.path}
                onClick={() => dispatch(reduxActions.setIsMobileMenu(false))}
                className={classnames(styles.subItem, {
                  [styles.current]: isCurrentPath(subItem.path)
                })}>
                {t(subItem.label)}
              </Link>
            ))}
          </div>
        )}
      </div>
    );
  };

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Containers-DashboardMenu')}>
      {menuItems.map((parentItem, index) => renderParentItems(parentItem, index))}
    </div>
  );
};

export default DashboardMenu;
