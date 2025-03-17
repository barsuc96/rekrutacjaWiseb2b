// lista płatności

// TODO - example of refactored mobile
import React, { ReactNode, useEffect, useMemo, useState } from 'react';
import classnames from 'classnames';
import { CheckCircle, ChevronDown, Clock, ExclamationCircle } from 'react-bootstrap-icons';
import { Trans, useTranslation } from 'react-i18next';
import { format, parseISO } from 'date-fns';
import { AngleDownIcon } from 'assets/icons';

import { useRWD } from 'hooks';
import { reduxActions, useDispatch } from 'store';
import { useGetSettlementsSummary, useGetSettlements } from 'api';
import { ISettlementsListItem, ISettlementsRequest } from 'api/types';
import { SearchInput, PageTitle, DateRangePicker, Button } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { Details } from './components';

import styles from 'theme/pages/Payments/Payments.module.scss';

// lista tabów
const tabs = [
  { key: 'PAID', label: <Trans>Wszystkie płatności</Trans> },
  { key: 'CURRENT', label: <Trans>Nadchodzące</Trans> },
  { key: 'EXPIRED', label: <Trans>Przedawnione</Trans> }
];

const DashboardPayments = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // domyślne parametry zapytania do API o listę płatności
  const defaultQueryParams = {
    page: 1,
    limit: 20
  };

  // rozwinięte płatności
  const [expandedRows, setExpandedRows] = useState<{ id: number; content: ReactNode }[]>([]);

  // parametry zapytania do API o listę płatności
  const [queryParams, setQueryParams] = useState<ISettlementsRequest>(defaultQueryParams);

  // pobranie listy płatności
  const { data: paymentsData } = useGetSettlements(queryParams);

  // pobranie podsumowania płatności
  const { data: paymentsSummaryData } = useGetSettlementsSummary();

  const summaryBoxes = useMemo(
    () =>
      [
        { type: 'paid', data: paymentsSummaryData?.paid },
        { type: 'upcoming', data: paymentsSummaryData?.upcoming },
        { type: 'expired', data: paymentsSummaryData?.expired }
      ].filter((item) => item),
    [paymentsSummaryData]
  );

  // obsługa rozwinięcia płatności
  const handleExpandRowToggle = (id: number) => {
    const expandedRow = expandedRows.some((item) => item.id === id);

    setExpandedRows((prevState) =>
      expandedRow
        ? prevState.filter((item) => item.id !== id)
        : [
            ...prevState,
            {
              id,
              content: <Details paymentId={id} />
            }
          ]
    );
  };

  // ustawienie breadcrumbs'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: 'Dashboard' },
        { name: t('Lista płatności') }
      ])
    );
  }, []);

  // funkcja resetująca filtry
  const resetFilters = () =>
    setQueryParams((prevState) => ({
      ...defaultQueryParams,
      status_type: prevState.status_type
    }));

  // czy są filtry zastosowane?
  const areFiltersApplied =
    !!queryParams.search_keyword || !!queryParams.date_from || !!queryParams.date_to;

  const columns: IColumn<ISettlementsListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        key: 'lp',
        align: 'center',
        renderCell: (item, index) => index + 1
      },
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'symbol',
        align: 'left',
        renderCell: (item) => <span className={styles.symbol}>{item.symbol}</span>
      },
      {
        title: <Trans>Data płatności</Trans>,
        dataIndex: 'payment_date',
        align: 'center',
        renderCell: (item) => format(parseISO(item.payment_date), 'dd-MM-yyyy')
      },
      {
        title: <Trans>Wartość</Trans>,
        dataIndex: 'amount_formatted',
        align: 'right',
        renderCell: (item) => `${item.amount_formatted} ${item.currency}`
      },
      {
        title: <Trans>Dni po terminie</Trans>,
        dataIndex: 'payment_diff_days',
        align: 'center',
        renderCell: (item) => (
          <span className={classnames(styles.days, styles.red)}>{item.payment_diff_days}</span>
        )
      },
      {
        title: '',
        key: 'details',
        align: 'center',
        renderCell: (item) => (
          <AngleDownIcon
            className={classnames(styles.arrow, {
              [styles.open]: expandedRows.some((row) => row.id === item.id)
            })}
            onClick={() => handleExpandRowToggle(item.id)}
          />
        )
      }
    ],
    [expandedRows]
  );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Payments')}>
      <PageTitle
        title={
          <>
            <Trans>Lista płatności</Trans>{' '}
            <span className="thin">({paymentsData?.total_count})</span>
          </>
        }
      />

      <div className={styles.summary}>
        {summaryBoxes.map((item) => (
          <div
            className={classnames(styles.box, {
              [styles.green]: item.type === 'paid',
              [styles.orange]: item.type === 'upcoming',
              [styles.red]: item.type === 'expired'
            })}
            key={item?.type}>
            <div className={styles.title}>
              {item.type === 'paid' ? (
                <>
                  <CheckCircle /> <Trans>Zapłacone</Trans>
                </>
              ) : item.type === 'upcoming' ? (
                <>
                  <Clock /> <Trans>Nadchodzące</Trans>
                </>
              ) : item.type === 'expired' ? (
                <>
                  <ExclamationCircle /> <Trans>Przedawnione</Trans>
                </>
              ) : null}
            </div>
            <div className={styles.items}>
              <div className={styles.item}>
                <div className={styles.label}>
                  <Trans>Ilość płatności</Trans>
                </div>
                <div className={styles.value}>{item.data?.payments.count}</div>
              </div>
              <div className={styles.item}>
                <div className={styles.label}>
                  <Trans>Ilość faktur</Trans>
                </div>
                <div className={styles.value}>{item.data?.invoices.count}</div>
              </div>
              <div className={classnames(styles.item, styles.amount)}>
                <div className={styles.label}>
                  <Trans>Wartość</Trans> ({item.data?.currency})
                </div>
                <div className={styles.value}>{item.data?.total.value_formatted}</div>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className={styles.filtersWrapper}>
        <SearchInput
          placeholder={t('Podaj symbol płatności')}
          value={queryParams.search_keyword}
          onChange={(value) =>
            setQueryParams((prevState) => ({ ...prevState, search_keyword: value }))
          }
        />

        <DateRangePicker
          dateRange={{
            from: queryParams.date_from,
            to: queryParams.date_to
          }}
          onChange={(range) =>
            setQueryParams((prevState) => ({
              ...prevState,
              date_from: range.from,
              date_to: range.to
            }))
          }
        />

        <Button disabled={!areFiltersApplied} onClick={resetFilters}>
          <Trans>Wyczyść filtrowanie</Trans>
        </Button>
      </div>

      <div className={styles.tabs}>
        {tabs.map((tab) => (
          <div
            key={tab.key}
            className={classnames(styles.tab, {
              [styles.active]: tab.key === (queryParams.status_type || 'PAID')
            })}
            onClick={() =>
              tab.key !== queryParams.status_type &&
              setQueryParams((prevState) => ({
                ...prevState,
                page: 1,
                status_type:
                  tab.key !== 'PAID' ? (tab.key as ISettlementsRequest['status_type']) : undefined
              }))
            }>
            {tab.label}
          </div>
        ))}
      </div>

      <div className={styles.tableWrapper}>
        {isMobile ? (
          <div className={styles.mobileList}>
            {paymentsData?.items.map((item) => (
              <div key={item.id} className={styles.mobileListItem}>
                <div className={styles.itemHeader}>{item.symbol}</div>
                <div className={styles.itemBody}>
                  <div>
                    <span className={styles.label}>
                      <Trans>Data płatności</Trans>
                    </span>
                    <span>{format(parseISO(item.payment_date), 'dd-MM-yyyy')}</span>
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Dni po terminie</Trans>
                    </span>
                    <span className={classnames(styles.days, styles.red)}>
                      {item.payment_diff_days}
                    </span>
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Wartość netto</Trans>
                    </span>
                    <span>
                      {item.amount_formatted} {item.currency}
                    </span>
                  </div>
                  <div>
                    <span className={styles.label}>
                      <Trans>Wartość brutto</Trans>
                    </span>
                    <strong>
                      {item.amount_formatted} {item.currency}
                    </strong>
                  </div>
                </div>
                <div className={styles.itemFooter}>
                  <button
                    onClick={() => handleExpandRowToggle(item.id)}
                    className={classnames({
                      [styles.open]: expandedRows.some((row) => row.id === item.id)
                    })}>
                    <Trans>szczegóły</Trans> <ChevronDown />
                  </button>
                </div>
                {expandedRows.some((row) => row.id === item.id) && (
                  <div className={styles.details}>
                    {expandedRows.find((row) => row.id === item.id)?.content}
                  </div>
                )}
              </div>
            ))}
          </div>
        ) : (
          <Table
            columns={columns}
            dataSource={paymentsData?.items || []}
            rowKey="id"
            pagination={{
              page: queryParams.page || 1,
              pagesCount: paymentsData?.total_pages || 1,
              onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
            }}
            expandedRowContents={expandedRows}
          />
        )}
      </div>
    </div>
  );
};

export default DashboardPayments;
