// lista zamówień

import React, { useEffect, useMemo, useState } from 'react';
import { useLocation } from 'react-router-dom';
import { format, parse } from 'date-fns';
import { ChevronRight, FiletypePdf, ThreeDotsVertical } from 'react-bootstrap-icons';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { reduxActions, useDispatch } from 'store';
import { useGetOrders, useGetOrdersUsers, useGetOrderExport } from 'api';
import { IOrderListItem, IOrdersRequest, IOrdersUserListItem } from 'api/types';
import {
  SearchInput,
  PageTitle,
  Status,
  Button,
  Select,
  Loader,
  DateRangePicker,
  DropDown,
  Link
} from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { Link as AppLink } from 'components/controls';

import styles from 'theme/pages/Orders/Orders.module.scss';

const Orders = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();
  const { pathname } = useLocation();

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2));

  // domyślne parametry zapytania api o listę zamówień
  const defaultQueryParams = {
    page: 1,
    limit: 20
  };

  // ID wybranego zamówienia na potrzeby pobrania PDF
  const [chosenOrderId, setChosenOrderId] = useState<number | null>(null);

  // parametry zapytania do API o listę zamówień
  const [queryParams, setQueryParams] = useState<IOrdersRequest>(defaultQueryParams);

  // pobranie listy zamówień
  const { data: ordersData } = useGetOrders(queryParams);

  // pobranie listy użytkowników w zamówieniach
  const { data: ordersUsersData } = useGetOrdersUsers();

  // export zamówienia do pdf
  const { isFetching: isOrderExporting } = useGetOrderExport(
    chosenOrderId || 0,
    { export_type: 'pdf' },
    {
      enabled: !!chosenOrderId,
      onSuccess: (data) => {
        const a = document.createElement('a');
        a.download = data.file_name;
        a.href = `data:image/gif;base64,${data.content}`;
        a.click();
      },
      onSettled: () => setChosenOrderId(null)
    }
  );

  // ustawienie breadcrumbs'ów po zamontowaniu komponentu
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: 'dashboard' },
        { name: t('Lista zamówień') }
      ])
    );
  }, []);

  // funkcja resetująca filtry
  const resetFilters = () => setQueryParams(defaultQueryParams);

  // czy jest zastosowany jakiś extra filtr?
  const areFiltersApplied =
    !!queryParams.search_keyword ||
    !!queryParams.order_date_from ||
    !!queryParams.order_date_to ||
    !!queryParams.user_ordering;

  // opcje w selektorze uźytkowników w filtrze
  const usersOptions = useMemo(
    () => ordersUsersData?.items.map((item) => ({ value: item.id, label: item.name, item })) || [],
    [ordersUsersData]
  );

  const renderExportButton = (id: number) =>
    isOrderExporting && chosenOrderId === id ? (
      <div className={styles.exportLoader}>
        <Loader />
      </div>
    ) : (
      <button
        className={styles.exportButton}
        onClick={() => !chosenOrderId && setChosenOrderId(id)}>
        <FiletypePdf /> <Trans>export</Trans>
      </button>
    );

  // kolumny tabelki z zamówieniami
  const columns: IColumn<IOrderListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        dataIndex: 'lp',
        align: 'center'
      },
      {
        title: <Trans>ID</Trans>,
        dataIndex: 'id',
        align: 'left',
        renderCell: (item) => (
          <AppLink
            className={styles.link}
            to={`${pathname.replace(`${urlPrefix}`, '')}/${item.id}`}>
            {item.id}
          </AppLink>
        )
      },
      {
        title: <Trans>Użytkownik</Trans>,
        dataIndex: 'user_name',
        align: 'left'
      },
      {
        title: <Trans>Status</Trans>,
        dataIndex: 'status',
        align: 'center',
        renderCell: (item) => (
          <Status icon={item.status.icon} message={item.status.label} color={item.status.color} />
        )
      },
      {
        title: <Trans>Liczba produktów</Trans>,
        dataIndex: 'products_total_count',
        align: 'center'
      },
      {
        title: <Trans>Data utworzenia</Trans>,
        dataIndex: 'create_date',
        align: 'center',
        renderCell: (item) => format(new Date(item.create_date), 'dd-MM-yyyy')
      },
      {
        title: <Trans>Wartość netto</Trans>,
        dataIndex: 'value_net',
        align: 'right',
        renderCell: (item) => `${item.value_net_formatted || '-'} ${item.currency}`
      },
      {
        title: <Trans>Wartość brutto</Trans>,
        dataIndex: 'value_gross',
        align: 'right',
        renderCell: (item) => `${item.value_gross_formatted || '-'} ${item.currency}`
      },
      {
        title: <Trans>Opcje</Trans>,
        key: 'actions',
        align: 'center',
        renderCell: (item) => renderExportButton(item.id)
      }
    ],
    [ordersUsersData, isOrderExporting, chosenOrderId]
  );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Orders')}>
      <PageTitle
        title={
          <>
            <Trans>Lista zamówień</Trans>{' '}
            <span className="thin">({ordersData?.total_count || 0})</span>
          </>
        }
      />
      <div className={styles.filtersWrapper}>
        <div>
          <SearchInput
            placeholder={t('Podaj symbol zamówienia')}
            value={queryParams.search_keyword}
            onChange={(value) =>
              setQueryParams((prevState) => ({ ...prevState, search_keyword: value }))
            }
          />

          <DateRangePicker
            dateRange={{
              from: queryParams.order_date_from
                ? format(parse(queryParams.order_date_from, 'dd-MM-yyyy', new Date()), 'yyyy-MM-dd')
                : undefined,
              to: queryParams.order_date_to
                ? format(parse(queryParams.order_date_to, 'dd-MM-yyyy', new Date()), 'yyyy-MM-dd')
                : undefined
            }}
            onChange={(range) =>
              setQueryParams((prevState) => ({
                ...prevState,
                order_date_from: format(new Date(range.from), 'dd-MM-yyyy'),
                order_date_to: format(new Date(range.to), 'dd-MM-yyyy')
              }))
            }
          />

          <Button disabled={!areFiltersApplied} onClick={resetFilters}>
            {t('Wyczyść filtrowanie')}
          </Button>
        </div>

        <div className={styles.userSelectWrapper}>
          <Select<IOrdersUserListItem>
            variant="bordered"
            placeholder={`${t('Wybierz użytkownika')}...`}
            options={usersOptions}
            value={queryParams.user_ordering}
            onChange={(item) =>
              setQueryParams((prevState) => ({
                ...prevState,
                user_ordering: item?.id
              }))
            }
          />
        </div>
      </div>

      {isMobile ? (
        <div className={styles.mobileList}>
          {ordersData?.items.map((item) => (
            <div key={item.id} className={styles.mobileListItem}>
              <div className={styles.line}>
                <span>
                  {item.lp}
                  <Link
                    to={`${pathname.replace(`${urlPrefix}`, '')}/${item.id}`}
                    className={styles.primary}>
                    <Trans>ID</Trans> {item.id}
                  </Link>
                </span>

                <DropDown
                  label={<ThreeDotsVertical />}
                  items={[
                    {
                      label: renderExportButton(item.id)
                    }
                  ]}
                  withDropdownIcon={false}
                />
              </div>
              <div className={styles.line}>
                <span>
                  <span className={styles.label}>
                    <Trans>Użytkownik</Trans>:
                  </span>
                  {item.user_name}
                </span>
              </div>
              <div className={styles.line}>
                <span>
                  <span className={styles.label}>
                    <Trans>Liczba produktów</Trans>:
                  </span>{' '}
                  {item.products_total_count}
                </span>
              </div>
              <div className={styles.line}>
                <div>
                  <span className={styles.label}>
                    <Trans>Data utworzenia</Trans>
                  </span>{' '}
                  <br />
                  {format(new Date(item.create_date), 'dd-MM-yyyy')}
                </div>
                <Status
                  icon={item.status.icon}
                  message={item.status.label}
                  color={item.status.color}
                />
              </div>
              <div className={styles.line}>
                <div>
                  <span className={styles.label}>
                    <Trans>Wartość netto</Trans>
                  </span>{' '}
                  <br />
                  {item.value_net_formatted} {item.currency}
                </div>

                <div className={styles.right}>
                  <span className={styles.label}>
                    <Trans>Wartość brutto</Trans>
                  </span>{' '}
                  <br />
                  <span className={styles.highlight}>
                    {item.value_gross_formatted} {item.currency}
                  </span>
                </div>
              </div>
              <div className={styles.line}>
                <div />
                <Link
                  to={`${pathname.replace(`${urlPrefix}`, '')}/${item.id}`}
                  className={styles.link}>
                  <Trans>szczegóły zamówienia</Trans> <ChevronRight />
                </Link>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <Table<IOrderListItem>
          columns={columns}
          dataSource={ordersData?.items || []}
          rowKey="id"
          pagination={{
            page: queryParams.page || 1,
            pagesCount: ordersData?.total_pages || 1,
            onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
          }}
        />
      )}
    </div>
  );
};

export default Orders;
