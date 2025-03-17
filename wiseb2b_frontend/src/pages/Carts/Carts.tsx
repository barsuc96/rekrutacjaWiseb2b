// lista koszyków
// TODO WIS-1468
import React, { useState, useEffect, useMemo, ReactNode } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useDeleteCart, useGetCarts } from 'api';
import { ICartListItem } from 'api/types';
import { reduxActions, useDispatch } from 'store';
import { PageTitle, Button, Modal, Link } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { CartPositions } from 'components/containers';

import styles from 'theme/pages/Carts/Carts.module.scss';
import { AngleDownIcon, TrashIcon } from 'assets/icons';
import CartMobile from './components/CartMobile';
import CartExportButton from './components/CartExportButton';

const DashboardCarts = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();

  // ustawienie breadcrums'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Lista koszyków') }
      ])
    );
  }, []);

  // parametry zapytania o listę koszyków
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 20
  });

  // ID koszyka do usunięcia
  const [cartIdToDelete, setCartIdToDelete] = useState<number | null>(null);

  // rozwinięte pozycje tabelki
  const [expandedRows, setExpandedRows] = useState<{ id: number; content: ReactNode }[]>([]);

  const { data: cartsData, refetch: refetchCartsData } = useGetCarts(queryParams, {
    onSuccess: (data) => {
      dispatch(reduxActions.setCurrentCartId(data.items[0]?.id) || null);
    }
  });

  const { mutate: deleteCart, isLoading: isDeletingCart } = useDeleteCart(Number(cartIdToDelete), {
    onSuccess: () => {
      refetchCartsData();
      setCartIdToDelete(null);
    }
  });

  const handleExpandRowToggle = (cartId: number) => {
    const isExpanded = expandedRows.some((item) => item.id === cartId);
    setExpandedRows((prevState) =>
      isExpanded
        ? prevState.filter((item) => item.id !== cartId)
        : [
            ...prevState,
            {
              id: cartId,
              content: (
                <div className={styles.expandedTableWrapper}>
                  <CartPositions cartId={cartId} variant="noEdit" />
                </div>
              )
            }
          ]
    );
  };

  const columns: IColumn<ICartListItem>[] = useMemo(
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
        key: 'name',
        align: 'left',
        renderCell: (item) => (
          <Link className={styles.link} to={`/cart/${item.id}`}>
            {item.name}
          </Link>
        )
      },
      {
        title: <Trans>Ilość produktów</Trans>,
        key: 'products_count',
        align: 'center',
        renderCell: (item) => <span className={styles.light}>{item.products_count}</span>
      },
      {
        title: <Trans>Data utworzenia</Trans>,
        key: 'create_datetime',
        align: 'right',
        renderCell: (item) => <span className={styles.light}>{item.create_datetime}</span>
      },
      {
        title: <Trans>Wartość netto</Trans>,
        key: 'value_net_formatted',
        align: 'right',
        renderCell: (item) => (
          <span className={styles.dark}>
            {item.value_net_formatted} {item.currency}
          </span>
        )
      },
      {
        title: <Trans>Wartość brutto</Trans>,
        key: 'value_gross_formatted',
        align: 'center',
        renderCell: (item) => (
          <span className={styles.light}>
            {item.value_gross_formatted} {item.currency}
          </span>
        )
      },
      {
        title: <Trans>Opcje</Trans>,
        key: 'options',
        align: 'center',
        renderCell: (item) => {
          return (
            <div className={styles.options}>
              <TrashIcon
                className={styles.deleteButton}
                onClick={() => {
                  setCartIdToDelete(item.id);
                }}
              />
              <CartExportButton id={item.id} />
            </div>
          );
        }
      },
      {
        title: <Trans>Szczegóły</Trans>,
        key: 'details',
        align: 'center',
        renderCell: (item) => (
          <AngleDownIcon
            onClick={() => handleExpandRowToggle(item.id)}
            className={classnames(styles.arrow, {
              [styles.open]: expandedRows.some((expandedItem) => expandedItem.id === item.id)
            })}
          />
        )
      }
    ],
    [expandedRows]
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Carts')}>
      <PageTitle
        title={
          <>
            <Trans>Lista koszyków</Trans> <span className="thin">({cartsData?.total_count})</span>
          </>
        }
      />

      <div className={styles.tableWrapper}>
        <Table<ICartListItem>
          columns={columns}
          mobileItem={(item, index) => (
            <CartMobile
              key={item.id}
              item={item}
              index={index}
              setCartIdToDelete={() => setCartIdToDelete(item.id)}
            />
          )}
          dataSource={cartsData?.items || []}
          rowKey="id"
          expandedRowContents={expandedRows}
          pagination={{
            page: queryParams.page,
            pagesCount: cartsData?.total_pages || 1,
            onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
          }}
        />
      </div>

      {!!cartIdToDelete && (
        <Modal
          title={t('Potwierdzenie usunięcia')}
          onClose={() => {
            setCartIdToDelete(null);
          }}>
          <div className={styles.confirmationModal}>
            <span className={styles.text}>
              <Trans>Czy napewno usunąć koszyk?</Trans>
            </span>

            <div className={styles.confirmationModalActions}>
              <Button
                ghost
                color="danger"
                onClick={() => {
                  setCartIdToDelete(null);
                }}>
                <Trans>Anuluj</Trans>
              </Button>
              <Button color="danger" loading={isDeletingCart} onClick={() => deleteCart()}>
                <Trans>Usuń</Trans>
              </Button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default DashboardCarts;
