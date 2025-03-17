// strona dashboardu

import React, { useEffect } from 'react';
import { Grid } from '@mui/material';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch } from 'store';
import { PageTitle } from 'components/controls';
import { Deliveries, Invoices, Payments, Summary, Orders } from './components';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const Dashboard = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  // ustawieni breadcrumbs'ów na starcie strony
  useEffect(() => {
    dispatch(reduxActions.setBreadcrumbs([{ name: t('Dashboard') }]));
  }, []);

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Dashboard')}>
      <PageTitle title={t('Dashboard')} />
      <Grid container columnSpacing="32px">
        <Grid item lg={9} xs={12}>
          <Summary />
          <Payments />
          <Orders />
          <Deliveries />
        </Grid>
        <Grid item lg={3} xs={12}>
          <Invoices />
        </Grid>
      </Grid>
    </div>
  );
};

export default Dashboard;
