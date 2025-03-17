// lista dostaw

import React, { useEffect, useMemo, useState } from 'react';
import { useLocation } from 'react-router-dom';
import { format } from 'date-fns';
import { Trans, useTranslation } from 'react-i18next';

import { reduxActions, useDispatch } from 'store';
import { useGetDeliveries } from 'api';
import { IDeliveryListItem, IDeliveriesRequest } from 'api/types';
import { SearchInput, PageTitle, Button, DateRangePicker, Link } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import styles from 'theme/pages/Deliveries/Deliveries.module.scss';
import classnames from 'classnames';

const Deliveries = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { pathname } = useLocation();

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2));

  // domyślne parametry zapytania api o listę dostaw
  const defaultQueryParams = {
    page: 1,
    limit: 20
  };

  // parametry zapytania do API o listę dostaw
  const [queryParams, setQueryParams] = useState<IDeliveriesRequest>(defaultQueryParams);

  // pobranie listy dostaw
  const { data: deliveriesData } = useGetDeliveries(queryParams);

  // ustawienie breadcrumbs'ów po zamontowaniu komponentu
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: 'dashboard' },
        { name: t('Lista dostaw') }
      ])
    );
  }, []);

  // funkcja resetująca filtry
  const resetFilters = () => setQueryParams(defaultQueryParams);

  // czy jest zastosowany jakiś extra filtr?
  const areFiltersApplied =
    !!queryParams.search_keyword || !!queryParams.date_from || !!queryParams.date_to;

  // kolumny tabelki z dostawami
  const columns: IColumn<IDeliveryListItem>[] = useMemo(
    () => [
      {
        title: <Trans>Symbol</Trans>,
        dataIndex: 'symbol',
        align: 'left',
        renderCell: (item) => (
          <Link to={`${pathname.replace(`${urlPrefix}`, '')}/${item.id}`}>{item.symbol}</Link>
        )
      },
      {
        title: <Trans>Adres</Trans>,
        dataIndex: 'receiver_address',
        align: 'left',
        renderCell: (item) =>
          `${item.receiver_address.street}, ${item.receiver_address.postal_code} ${item.receiver_address.city}`
      },
      {
        title: <Trans>Liczba produktów</Trans>,
        dataIndex: 'products_count',
        align: 'center'
      },
      {
        title: <Trans>Data dostawy</Trans>,
        dataIndex: 'delivery_date',
        align: 'center',
        renderCell: (item) => format(new Date(item.delivery_date), 'dd-MM-yyyy')
      },
      {
        title: <Trans>Wartość netto</Trans>,
        dataIndex: 'order_value_net',
        align: 'right',
        renderCell: (item) => `${item.order_value_net_formatted || '-'} ${item.order_currency}`
      },
      {
        title: <Trans>Wartość brutto</Trans>,
        dataIndex: 'order_value_gross',
        align: 'right',
        renderCell: (item) => `${item.order_value_gross_formatted || '-'} ${item.order_currency}`
      }
    ],
    []
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Deliveries')}>
      <PageTitle
        title={
          <>
            <Trans>Lista dostaw</Trans>{' '}
            <span className="thin">({deliveriesData?.total_count || 0})</span>
          </>
        }
      />
      <div className={styles.filtersWrapper}>
        <SearchInput
          placeholder={t('Podaj symbol zamówienia')}
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
          {t('Wyczyść filtrowanie')}
        </Button>
      </div>

      <Table<IDeliveryListItem>
        columns={columns}
        dataSource={deliveriesData?.items || []}
        rowKey="id"
        pagination={{
          page: queryParams.page || 1,
          pagesCount: deliveriesData?.total_pages || 1,
          onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
        }}
      />
    </div>
  );
};

export default Deliveries;
