// lista faktur

import React, { useState } from 'react';
import { format } from 'date-fns';
import classnames from 'classnames';
import { Trans } from 'react-i18next';
import { ChevronDown, ChevronRight } from 'react-bootstrap-icons';

import { useGetDashboardInvoices } from 'api';
import { Link } from 'components/controls';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const Invoices = () => {

  // lista ID'ków rozwiniętych faktur
  const [expandedIds, setExpandedIds] = useState<number[]>([]);

  // pobranie listy faktur
  const { data: dashboardInvoicesData } = useGetDashboardInvoices();

  const toggleExpanded = (id: number) =>
    setExpandedIds((prevState) =>
      prevState.includes(id) ? prevState.filter((item) => item !== id) : [...prevState, id]
    );

  return (
    <div
      className={classnames(
        styles.listWrapper,
        styles.invoices,
        'StylePath-Pages-Dashboard-Components-Invoices'
      )}>
      <div className={styles.listHeader}>
        <Trans>Faktury</Trans>
        <Link
          to="/dashboard/payments"
          className={styles.link}>
          <Trans>więcej</Trans> <ChevronRight />
        </Link>
      </div>

      {(dashboardInvoicesData?.items || []).map((invoice) => (
        <div className={styles.invoice} key={invoice.id}>
          <div
            className={classnames(styles.header, {
              [styles.expanded]: expandedIds.includes(invoice.id)
            })}
            onClick={() => toggleExpanded(invoice.id)}>
            {invoice.symbol} <ChevronDown />
          </div>
          {expandedIds.includes(invoice.id) && (
            <div className={styles.content}>
              <div className={styles.box}>
                <div className={styles.label}>
                  <Trans>Data płatności</Trans>
                </div>
                {invoice.payment_datetime &&
                  format(new Date(invoice.payment_datetime), 'dd-MM-yyyy')}
              </div>
              <div className={styles.box}>
                <div className={styles.label}>
                  <Trans>Data utworzenia</Trans>
                </div>
                {invoice.issued_datetime && format(new Date(invoice.issued_datetime), 'dd-MM-yyyy')}
              </div>
              <div className={styles.box}>
                <div className={styles.label}>
                  <Trans>Wartość brutto</Trans>
                </div>
                {invoice.value_formatted} {invoice.currency}
              </div>
            </div>
          )}
        </div>
      ))}
    </div>
  );
};

export default Invoices;
