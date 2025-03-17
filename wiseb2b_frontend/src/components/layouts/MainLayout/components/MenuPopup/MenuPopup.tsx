// główny layout - mobilny popup menu

import React from 'react';
import classnames from 'classnames';
import { X } from 'react-bootstrap-icons';

import { reduxActions, useDispatch, useSelector } from 'store';
import { DashboardMenu } from 'components/containers';
import { userRolesAccessRoutes } from 'components/layouts/DashboardSubLayout';

import styles from 'theme/components/layouts/MainLayout/components/MenuPopup/MenuPopup.module.scss';
import { Trans } from 'react-i18next';

const MenuPopup = () => {
  const dispatch = useDispatch();
  const { profile } = useSelector((state) => state.auth);

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-MenuPopup'
      )}>
      <div className={styles.topBox}>
        <span className={styles.label}>
          <Trans>Zalogowany jako</Trans>:
        </span>
        <span className={styles.name}>
          {profile?.first_name} {profile?.last_name}
        </span>
        <span className={styles.company}>{profile?.customer.name}</span>
        <button
          onClick={() => dispatch(reduxActions.setIsMobileMenu(false))}
          className={styles.closeButton}>
          <X />
        </button>
      </div>
      <div className={styles.menuBox}>
        {profile?.role && (
          <DashboardMenu accessRoutes={userRolesAccessRoutes()[`${profile.role}`]} />
        )}
      </div>
    </div>
  );
};

export default MenuPopup;
