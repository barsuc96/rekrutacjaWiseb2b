// lista zamówień

import React from 'react';
import { Trans } from 'react-i18next';
import { ChevronRight } from 'react-bootstrap-icons';
import { useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';

import { useGetDashboardOrders, useGetDashboardOrdersSummary } from 'api';
import { IDashboardOrderListItem } from 'api/types';
import { Table, Status, Link } from 'components/controls';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';
import classnames from 'classnames';

const Orders = () => {
  // TODO custom hook z dwóch poniższych?
  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down('md'));

  // pobranie indormacja o zamówieniach
  const { data: dashboardOrdersData } = useGetDashboardOrders();

  // pobranie podsumowania zamówień
  const { data: dashboardOrdersSummaryData } = useGetDashboardOrdersSummary();

  return (
    <div className={classnames(styles.listWrapper, 'StylePath-Pages-Dashboard-Components-Orders')}>
      <div className={classnames(styles.listHeader, styles.blue)}>
        <Trans>Lista zamówień</Trans>
        <Link to="/dashboard/orders" className={styles.link}>
          <Trans>więcej</Trans> <ChevronRight />
        </Link>
      </div>

      <div className={styles.tableWrapper}>
        {isMobile ? (
          <div className={styles.mobileList}>
            {dashboardOrdersData?.items.map((item) => (
              <div key={item.id} className={styles.mobileListItem}>
                <div className={styles.itemHeader}>{item.symbol}</div>
                <div className={styles.details}>
                  <div>
                    <span className={styles.label}>
                      <Trans>Data</Trans>
                    </span>
                    <span>{item.date_order}</span>
                  </div>
                  <div>
                    <Status
                      icon={item.status.icon}
                      message={item.status.label}
                      color={item.status.color}
                    />
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Do zapłaty</Trans>
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
          <Table<IDashboardOrderListItem>
            columns={[
              {
                title: <Trans>Symbol</Trans>,
                dataIndex: 'symbol',
                align: 'left',
                renderCell: (item) => <span className="promaryText">{item.symbol}</span>
              },
              {
                title: <Trans>Data</Trans>,
                dataIndex: 'date_order'
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
                title: <Trans>Do zapłaty</Trans>,
                dataIndex: 'value',
                align: 'right',
                renderCell: (item) => `${item.value_formatted} ${item.currency}`
              }
            ]}
            dataSource={dashboardOrdersData?.items || []}
            rowKey="symbol"
          />
        )}
      </div>

      <div className={styles.listFooter}>
        <div className={styles.summary}>
          <div className={styles.title}>
            <Trans>Podsumowanie zamówień</Trans>
          </div>
          <div className={styles.items}>
            <div>
              <span>
                <Trans>Płatności po terminie</Trans>:
              </span>{' '}
              {dashboardOrdersSummaryData?.expired.count}
            </div>
            <div>
              <span>
                <Trans>Nadchodzące</Trans>:
              </span>{' '}
              {dashboardOrdersSummaryData?.upcoming.count}
            </div>
            <div>
              <span>
                <Trans>Zapłacone</Trans>:
              </span>{' '}
              {dashboardOrdersSummaryData?.paid.count}
            </div>
          </div>
        </div>

        <div className={styles.total}>
          <div className={styles.title}>
            <Trans>Razem</Trans>:
          </div>
          <div className={styles.prices}>
            <span className={styles.gross}>
              <strong>
                {dashboardOrdersSummaryData?.total.value_formatted}{' '}
                {dashboardOrdersSummaryData?.total.currency}
              </strong>
            </span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Orders;
