// lista dokumentów

import React, { useState, useEffect, useMemo } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { useGetDocuments, useGetDocumentsSummary } from 'api';
import { IDocumentListItem, IDocumentsRequest } from 'api/types';
import { PageTitle } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import styles from 'theme/pages/Documents/Documents.module.scss';

const Documents = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  // zakładki
  const tabs: {
    type: IDocumentsRequest['document_type'];
    name: string;
    key: 'proforma' | 'invoices' | 'corrections';
  }[] = [
    {
      type: 'PROFORMA',
      name: t('Proforma'),
      key: 'proforma'
    },
    {
      type: 'INVOICE',
      name: t('Faktury'),
      key: 'invoices'
    },
    {
      type: 'CORRECTIVE_INVOICE',
      name: t('Korekty'),
      key: 'corrections'
    }
  ];

  // parametry zapytania o listę dokumentów
  const [queryParams, setQueryParams] = useState<IDocumentsRequest>({
    page: 1,
    limit: 20,
    document_type: 'PROFORMA'
  });

  // pobranie podsumowania
  const { data: documentsSummaryData } = useGetDocumentsSummary();

  // pobranie listy dokumentów
  const { data: documentsData } = useGetDocuments(queryParams);

  // ustawieni breadcrumbs'ów
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Moje dokumenty') }
      ])
    );
  }, []);

  const columns: IColumn<IDocumentListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        key: 'lp',
        align: 'center',
        renderCell: (item, index) => index + 1,
        width: 50
      },
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'symbol',
        align: 'left',
        renderCell: (item) => <span className={styles.light}>{item.symbol}</span>
      },
      {
        title: <Trans>Data płatności</Trans>,
        dataIndex: 'payment_datetime',
        align: 'right',
        renderCell: (item) => <span className={styles.light}>{item.payment_datetime}</span>
      },
      {
        title: <Trans>Data utworzenia</Trans>,
        dataIndex: 'create_datetime',
        align: 'right',
        renderCell: (item) => <span className={styles.light}>{item.create_datetime}</span>
      },
      {
        title: <Trans>Wartość brutto</Trans>,
        dataIndex: 'value_gross',
        align: 'right',
        renderCell: (item) => (
          <span className={styles.light}>
            {item.value_gross_formatted} {item.currency}
          </span>
        )
      }
    ],
    []
  );

  const renderMobileItem = (item: IDocumentListItem) => (
    <div key={item.id} className={styles.mobileListItem}>
      <div className={styles.itemHeader}>{item.symbol}</div>
      <div className={styles.itemBody}>
        <div>
          <span className={styles.label}>
            <Trans>Data utworzenia</Trans>
          </span>
          {item.create_datetime}
        </div>
        <div>
          <span className={styles.label}>
            <Trans>Data płatności</Trans>
          </span>
          {item.payment_datetime}
        </div>
        <div>
          <span className={styles.label}>
            <Trans>Wartość brutto</Trans>
          </span>
          <strong>
            {item.value_gross_formatted} {item.currency}
          </strong>
        </div>
      </div>
    </div>
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Documents')}>
      <PageTitle
        title={
          <>
            <Trans>Moje dokumenty</Trans>{' '}
            <span className="thin">({documentsData?.total_count})</span>
          </>
        }
      />

      <div className={styles.summary}>
        {Object.entries(documentsSummaryData || {}).map(([key, item]) => (
          <div
            className={classnames(styles.summaryBox, {
              [styles.active]:
                queryParams.document_type === tabs.find((tab) => tab.key === key)?.type
            })}
            key={key}
            onClick={() =>
              setQueryParams((prevState) => ({
                ...prevState,
                document_type: tabs.find((tab) => tab.key === key)?.type || 'PROFORMA',
                page: 1
              }))
            }>
            <div className={styles.summaryName}>
              <img className={styles.icon} src={item.icon} alt="icon" />
              {item.name}
            </div>
            <div className={styles.summaryCounter}>{item.counter}</div>
          </div>
        ))}
      </div>

      <div className={styles.tabsHeader}>
        {tabs.map((item) => (
          <div
            key={item.type}
            className={classnames(styles.tabsBox, {
              [styles.active]: queryParams.document_type === item.type
            })}
            onClick={() =>
              setQueryParams((prevState) => ({ ...prevState, document_type: item.type, page: 1 }))
            }>
            <div className={styles.summaryName}>{item.name}</div>
          </div>
        ))}
      </div>

      <div className={styles.tableWrapper}>
        <Table<IDocumentListItem>
          columns={columns}
          dataSource={documentsData?.items || []}
          rowKey="id"
          mobileItem={renderMobileItem}
          pagination={{
            page: queryParams.page || 1,
            pagesCount: documentsData?.total_pages || 1,
            onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
          }}
        />
      </div>
    </div>
  );
};

export default Documents;
