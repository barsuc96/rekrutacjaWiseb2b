// główny layout - mobilne menu dolne

import React, { useCallback } from 'react';
import { FileEarmarkText, Person, HouseFill, List } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';
import classnames from 'classnames';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useAppNavigate } from 'hooks';

import { CartsButton, MobileMenu } from 'components/containers';

import styles from 'theme/components/layouts/MainLayout/components/BottomMenu/BottomMenu.module.scss';

const BottomMenu = () => {
  const dispatch = useDispatch();
  const { profile } = useSelector((state) => state.auth);
  const { isMobileMenu } = useSelector((state) => state.ui);
  const navigate = useAppNavigate();
  const { pathname } = useLocation();

  // TODO wydzielic w inne miejsce i usunąć duplikaty
  const isCurrentPath = useCallback(
    (testedPath?: string) =>
      testedPath ? new RegExp(`^/[^\\/]+${testedPath}$`).test(pathname) : false,
    [pathname]
  );

  const items =
    profile?.role !== 'ROLE_OPEN_PROFILE'
      ? [
          {
            icon: <HouseFill />,
            label: <Trans>Start</Trans>,
            onClick: () => {
              navigate('/');
              dispatch(reduxActions.setIsMobileMenu(false));
            },
            active: pathname.split('/').filter((item) => item).length === 1
          },
          {
            icon: <MobileMenu />,
            label: <Trans>Produkty</Trans>,
            onClick: () => {
              dispatch(reduxActions.setIsMobileMenu(false));
            },
            active: isCurrentPath('/products')
          },
          {
            icon: <FileEarmarkText />,
            label: <Trans>Listy</Trans>,
            onClick: () => {
              navigate('/dashboard/shopping-lists');
            },
            active: isCurrentPath('/dashboard/shopping-lists')
          },
          {
            icon: <CartsButton />,
            label: <Trans>Koszyk</Trans>
          },
          {
            icon: <Person />,
            label: <Trans>Konto</Trans>,
            onClick: () => dispatch(reduxActions.setIsMobileMenu(!isMobileMenu))
          }
        ]
      : [
          {
            icon: <HouseFill />,
            label: <Trans>Start</Trans>,
            onClick: () => {
              navigate('/');
              dispatch(reduxActions.setIsMobileMenu(false));
            },
            active: pathname.split('/').filter((item) => item).length === 1
          },
          {
            icon: <Person />,
            label: <Trans>Loguj</Trans>,
            onClick: () => {
              navigate('/login?return_url=/');
            }
          },
          {
            icon: <MobileMenu />,
            label: <Trans>Produkty</Trans>,
            onClick: () => {
              dispatch(reduxActions.setIsMobileMenu(false));
            },
            active: isCurrentPath('/products')
          }
        ];

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-BottomMenu'
      )}>
      {items.map((item, index) => (
        <div
          key={index}
          className={classnames(styles.menuItem, { [styles.active]: item.active })}
          onClick={item.onClick}>
          {item.icon}
          {item.label}
        </div>
      ))}
    </div>
  );
};

export default BottomMenu;
