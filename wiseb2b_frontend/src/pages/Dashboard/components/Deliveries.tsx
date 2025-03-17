// lista dostaw

import React from 'react';
import { Trans } from 'react-i18next';
import { ChevronRight } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';

import { useGetDashboardDeliveries, useGetDashboardDeliveriesSummary } from 'api';
import { IDashboardDeliveryListItem } from 'api/types';
import { Status, Table, Link } from 'components/controls';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const Deliveries = () => {
  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down('md'));

  // pobranie listy dostaw
  const { data: dashboardDeliveriesData } = useGetDashboardDeliveries();

  // pobranie podsumowania listy dostaw
  const { data: dashboardDeliveriesSummaryData } = useGetDashboardDeliveriesSummary();

  return (
    <div
      className={classnames(styles.listWrapper, 'StylePath-Pages-Dashboard-Components-Deliveries')}>
      <div className={classnames(styles.listHeader, styles.violet)}>
        <Trans>Lista dostaw</Trans>
        <Link to="/dashboard/deliveries" className={styles.link}>
          <Trans>więcej</Trans> <ChevronRight />
        </Link>
      </div>

      <div className={styles.tableWrapper}>
        {isMobile ? (
          <div className={styles.mobileList}>
            {dashboardDeliveriesData?.items.map((item) => (
              <div key={item.id} className={styles.mobileListItem}>
                <div className={styles.itemHeader}>{item.symbol}</div>
                <div className={styles.details}>
                  <div>
                    <Status
                      icon={item.status.icon}
                      message={item.status.label}
                      color={item.status.color}
                    />
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Wartość</Trans>
                    </span>
                    <span>
                      {item.value_formatted} {item.currency}
                    </span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <Table<IDashboardDeliveryListItem>
            columns={[
              {
                title: <Trans>Symbol</Trans>,
                dataIndex: 'symbol',
                align: 'left',
                renderCell: (item) => <span className={styles.primaryText}>{item.symbol}</span>
              },
              {
                title: <Trans>Status</Trans>,
                dataIndex: 'status',
                align: 'center',
                renderCell: (item) => (
                  <Status
                    icon={item.status.icon}
                    message={item.status.label}
                    color={item.status.color}
                  />
                )
              },
              {
                title: <Trans>Wartość</Trans>,
                dataIndex: 'value',
                align: 'right',
                renderCell: (item) => `${item.value_formatted} ${item.currency}`
              }
            ]}
            dataSource={dashboardDeliveriesData?.items || []}
            rowKey="id"
          />
        )}
      </div>

      <div className={styles.listFooter}>
        <div className={styles.summary}>
          <div className={styles.title}>
            <Trans>Podsumowanie dostaw</Trans>
          </div>
          <div className={styles.items}>
            <div>
              <span>
                <Trans>Dostarczone</Trans>:
              </span>{' '}
              {dashboardDeliveriesSummaryData?.done.count}
            </div>
            <div>
              <span>
                <Trans>W drodze</Trans>:
              </span>{' '}
              {dashboardDeliveriesSummaryData?.inway.count}
            </div>
            <div>
              <span>
                <Trans>Do opłacenia</Trans>:
              </span>{' '}
              {dashboardDeliveriesSummaryData?.topay.count}
            </div>
          </div>
        </div>

        {/* <div className={styles.total}>
          <div className={styles.title}>
            <Trans>Razem</Trans>:
          </div>
          <div className={styles.prices}>
            <span className={styles.gross}>
              <strong>
                {dashboardDeliveriesSummaryData?.total.value_formatted}{' '}
                {dashboardDeliveriesSummaryData?.total.currency}
              </strong>
            </span>
          </div>
        </div> */}
      </div>
    </div>
  );
};

export default Deliveries;
