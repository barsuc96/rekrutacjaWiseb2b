// listy płatności

import React, { useState } from 'react';
import classnames from 'classnames';
import { format } from 'date-fns';
import { Trans } from 'react-i18next';
import { ChevronRight } from 'react-bootstrap-icons';
import { useTheme } from '@mui/material/styles';
import useMediaQuery from '@mui/material/useMediaQuery';

import { useGetDashboardSettlements, useGetDashboardSettlementsSummary } from 'api';
import { IDashboardSettlementsListItem } from 'api/types';
import { Table, Link } from 'components/controls';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const Payments = () => {
  const theme = useTheme();
  const isMobile = useMediaQuery(theme.breakpoints.down('md'));

  // rodzaj aktyenej listy
  const [activeTab, setActiveTab] = useState<'upcoming' | 'expired' | 'all'>('all');

  // pobranie informacji o płatnościach
  const { data: dashboardSettlementsData } = useGetDashboardSettlements();

  // pobranie podsumowania płatności
  const { data: dashboardSettlementsSummaryData } = useGetDashboardSettlementsSummary();

  return (
    <div
      className={classnames(styles.listWrapper, 'StylePath-Pages-Dashboard-Components-Payments')}>
      <div className={classnames(styles.listHeader, styles.orange)}>
        <Trans>Lista płatności</Trans>
        <Link to="/dashboard/payments" className={styles.link}>
          <Trans>więcej</Trans> <ChevronRight />
        </Link>
      </div>

      <div className={styles.listTabs}>
        <div
          className={classnames(styles.tab, { [styles.active]: activeTab === 'all' })}
          onClick={() => setActiveTab('all')}>
          <Trans>Nierozliczone</Trans>
        </div>
        <div
          className={classnames(styles.tab, { [styles.active]: activeTab === 'upcoming' })}
          onClick={() => setActiveTab('upcoming')}>
          <Trans>Nadchodzące</Trans>
        </div>
        <div
          className={classnames(styles.tab, { [styles.active]: activeTab === 'expired' })}
          onClick={() => setActiveTab('expired')}>
          <Trans>Przedawnione</Trans>
        </div>
      </div>

      <div className={styles.tableWrapper}>
        {isMobile ? (
          <div className={styles.mobileList}>
            {dashboardSettlementsData?.[activeTab]?.map((item) => (
              <div key={item.symbol} className={styles.mobileListItem}>
                <div className={styles.itemHeader}>{item.symbol}</div>
                <div className={styles.details}>
                  <div>
                    <span className={styles.label}>
                      <Trans>Data płatności</Trans>
                    </span>
                    <span>{format(new Date(item.date), 'dd-MM-yyyy')}</span>
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Do zapłaty</Trans>
                    </span>
                    <span>
                      {item.value_formatted} {item.currency}
                    </span>
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Dni po terminie</Trans>
                    </span>
                    <span className={styles.redText}>{item.payment_diff_days}</span>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <Table<IDashboardSettlementsListItem>
            columns={[
              {
                title: <Trans>Symbol</Trans>,
                dataIndex: 'symbol',
                align: 'left',
                renderCell: (item) => <span className="primaryText">{item.symbol}</span>
              },
              {
                title: <Trans>Data płatności</Trans>,
                dataIndex: 'date',
                align: 'center',
                renderCell: (item) => (
                  <span className="grayText">{format(new Date(item.date), 'dd-MM-yyyy')}</span>
                )
              },
              {
                title: <Trans>Do zapłaty</Trans>,
                dataIndex: 'value',
                align: 'right',
                renderCell: (item) => `${item.value_formatted} ${item.currency}`
              },
              {
                title: <Trans>Dni po terminie</Trans>,
                dataIndex: 'payment_diff_days',
                align: 'right',
                renderCell: (item) => <span className="redText">{item.payment_diff_days}</span>
              }
            ]}
            dataSource={dashboardSettlementsData?.[activeTab] || []}
            rowKey="symbol"
          />
        )}
      </div>

      <div className={styles.listFooter}>
        <div className={styles.summary}>
          <div className={styles.title}>
            <Trans>Podsumowanie płatności</Trans>
          </div>
          <div className={styles.items}>
            <div>
              <span>
                <Trans>Płatności po terminie</Trans>:
              </span>{' '}
              {dashboardSettlementsSummaryData?.expired.count}
            </div>
            <div>
              <span>
                <Trans>Nadchodzące</Trans>:
              </span>{' '}
              {dashboardSettlementsSummaryData?.upcoming.count}
            </div>
          </div>
        </div>

        <div className={styles.total}>
          <div className={styles.title}>
            <Trans>Razem do zapłacenia</Trans>:
          </div>
          <div className={styles.prices}>
            <span className={styles.gross}>
              <strong>
                {dashboardSettlementsSummaryData?.total.value_formatted}{' '}
                {dashboardSettlementsSummaryData?.total.currency}
              </strong>
            </span>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Payments;
