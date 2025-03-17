// sublayout dashbordu - menu w sidebarze

import React from 'react';
import { Outlet } from 'react-router';
import { Grid } from '@mui/material';
import classnames from 'classnames';

import { useSelector } from 'store';
import { Container } from 'components/controls';

import { DashboardMenu } from 'components/containers';
import { HeaderTopBar, BottomMenu } from 'components/layouts/MainLayout/components';
import { userRolesAccessRoutes } from 'components/layouts/DashboardSubLayout';

import styles from 'theme/components/layouts/ManagmentPanelLayout/ManagmentPanelLayout.module.scss';

const ManagementPanelLayout = () => {
  const { profile } = useSelector((state) => state.auth);

  const isAdminPanel = profile?.role === 'ROLE_ADMIN' || profile?.role === 'ROLE_TRADER';

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-ManagmentPanelLayout'
      )}>
      <main>
        <header>
          <HeaderTopBar isAdminPanel />
        </header>
        <div className={styles.content}>
          <Container>
            <Grid container columnSpacing="32px">
              <Grid item md={2} xs={12} className={styles.menuWrapper}>
                {profile?.role && (
                  <DashboardMenu
                    isAdminPanel
                    accessRoutes={userRolesAccessRoutes(isAdminPanel)[`${profile.role}`]}
                  />
                )}
              </Grid>
              <Grid item md={10} xs={12}>
                <Outlet />
              </Grid>
            </Grid>
          </Container>
        </div>
        <BottomMenu />
      </main>
    </div>
  );
};

export default ManagementPanelLayout;
