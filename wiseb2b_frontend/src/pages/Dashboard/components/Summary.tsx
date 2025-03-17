// boxy z podsumowaniem

import React from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { useGetDashboardSummary } from 'api';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const Summary = () => {
  // pobranie danych z API
  const { data: dashboardSummaryData } = useGetDashboardSummary();

  return (
    <div className={classnames(styles.summary, 'StylePath-Pages-Dashboard-Components-Summary')}>
      <div className={styles.box}>
        <Trans>Przedawnione płatności</Trans>
        <span className={classnames(styles.value, styles.red)}>
          {dashboardSummaryData?.expired_payments.count}
        </span>
      </div>
      <div className={styles.box}>
        <Trans>Wartość sprzedaży</Trans>
        <span className={styles.value}>
          {dashboardSummaryData?.sales.value_formatted} {dashboardSummaryData?.sales.currency}
        </span>
      </div>
      <div className={styles.box}>
        <Trans>Wstrzymane dostawy</Trans>
        <span className={styles.value}>{dashboardSummaryData?.suspended_deliveries.count}</span>
      </div>
      <div className={styles.box}>
        <Trans>Zamówienia</Trans>
        <span className={styles.value}>{dashboardSummaryData?.orders.count}</span>
      </div>
    </div>
  );
};

export default Summary;
