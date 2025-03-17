// strona z listą list zakupowych

import React, { useMemo, useEffect, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { reduxActions, useDispatch } from 'store';
import { useGetShoppingLists, useDeleteShoppingList } from 'api';
import { IOrdersRequest, IShoppingListListItem } from 'api/types';
import { PageTitle, Button, Modal } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { ShoppingListForm, ShoppingListPositions } from './components';

import { ChevronDown, PlusCircle, Trash } from 'react-bootstrap-icons';

import styles from 'theme/pages/ShoppingLists/ShoppingLists.module.scss';

const ShoppingLists = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // parametry zapytania do API o listy zakupowe
  const [queryParams, setQueryParams] = useState<IOrdersRequest>({
    page: 1,
    limit: 20
  });

  // lista zakupowa do usunięcia
  const [itemToRemove, setItemToRemove] = useState<IShoppingListListItem | null>(null);

  // aktualnie wybrana lista zakupowa
  const [currentShoppingListId, setCurrentShoppingListId] = useState<number | null>(null);

  // czy jest modal z tworzeniem listy zakupowej
  const [isCreateModal, setIsCreateModal] = useState(false);

  // pobranie list zakupowych
  const { data: shoppingListsData, refetch: refetchShoppingLists } =
    useGetShoppingLists(queryParams);

  // kasowanie listy zakupowej
  const { mutate: deleteShoppingList, isLoading: isDeleting } = useDeleteShoppingList(
    itemToRemove?.id || 0,
    {
      onSuccess: () => {
        setItemToRemove(null);
        refetchShoppingLists();
      }
    }
  );

  // ustawienie breadcrumbsów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Listy zakupowe') }
      ])
    );
  }, []);

  const columns: IColumn<IShoppingListListItem>[] = useMemo(
    () => [
      {
        title: <Trans>LP</Trans>,
        dataIndex: 'lp',
        align: 'center',
        width: 50
      },
      {
        title: <Trans>Nazwa listy</Trans>,
        dataIndex: 'name',
        align: 'left',
        renderCell: (item) => (
          <button
            className={styles.link}
            onClick={() => {
              setCurrentShoppingListId(item.id);
            }}>
            {item.name}
          </button>
        )
      },
      {
        title: <Trans>Liczba produktów</Trans>,
        dataIndex: 'products_count',
        align: 'center'
      },
      {
        title: <Trans>Data utworzenia</Trans>,
        dataIndex: 'create_datetime',
        align: 'left'
      },
      {
        title: <Trans>Opis listy</Trans>,
        dataIndex: 'description',
        align: 'left'
      },
      {
        title: <Trans>Opcje</Trans>,
        key: 'options',
        align: 'center',
        width: 80,
        renderCell: (item) => {
          return (
            <Trash
              className={styles.deleteButton}
              onClick={() => {
                setItemToRemove(item);
              }}
            />
          );
        }
      }
    ],
    []
  );

  const renderCreateShoppingListButton = () => (
    <Button onClick={() => setIsCreateModal(true)}>
      <PlusCircle /> <Trans>Utwórz nową liste zakupową</Trans>
    </Button>
  );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-ShoppingLists')}>
      <PageTitle
        title={
          <div className={styles.titleWrapper}>
            <div className={styles.text}>
              <Trans>Listy zakupowe</Trans>{' '}
              <span className="thin">({shoppingListsData?.total_count})</span>
            </div>

            <div className={styles.action}>{renderCreateShoppingListButton()}</div>
          </div>
        }
      />

      <div className={classnames(styles.action, styles.mobileAction)}>
        {renderCreateShoppingListButton()}
      </div>

      {isMobile ? (
        <div className={styles.mobileList}>
          {shoppingListsData?.items.map((item) => (
            <div key={item.id} className={styles.mobileListItem}>
              <div className={styles.itemHeader}>
                {item.lp}
                <span className={styles.name}>{item.name}</span>
              </div>
              <div className={styles.itemBody}>
                <div>
                  <span className={styles.label}>
                    <Trans>Liczba produktów</Trans>:
                  </span>
                  <strong>{item.products_count}</strong>
                </div>
                <div>
                  <span className={styles.label}>
                    <Trans>Data utworzenia</Trans>:
                  </span>
                  {item.create_datetime}
                </div>
                {item.description && (
                  <div>
                    <span className={styles.label}>
                      <Trans>Opis</Trans>:
                    </span>{' '}
                    {item.description}
                  </div>
                )}
              </div>
              <div className={styles.itemFooter}>
                <Trash
                  className={styles.deleteButton}
                  onClick={() => {
                    setItemToRemove(item);
                  }}
                />

                <button
                  className={styles.link}
                  onClick={() => {
                    setCurrentShoppingListId(item.id);
                  }}>
                  <Trans>zobacz szczegóły</Trans> <ChevronDown />
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className={styles.tableWrapper}>
          <Table<IShoppingListListItem>
            columns={columns}
            dataSource={shoppingListsData?.items || []}
            rowKey="id"
            pagination={{
              page: queryParams.page || 1,
              pagesCount: shoppingListsData?.total_pages || 1,
              onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
            }}
          />
        </div>
      )}

      {isCreateModal && (
        <Modal
          onClose={() => setIsCreateModal(false)}
          title={t('Utwórz nową liste zakupową')}
          fullScreen={isMobile}>
          <ShoppingListForm
            onSuccess={refetchShoppingLists}
            onCancel={() => setIsCreateModal(false)}
          />
        </Modal>
      )}

      {itemToRemove && (
        <Modal title={t('Potwierdzenie usunięcia')} onClose={() => setItemToRemove(null)}>
          <div className={styles.confirmationModal}>
            <Trans>Czy na pewno chcesz usunąć liste zakupową</Trans>:{' '}
            <strong>{itemToRemove.name}</strong>
            <div className={styles.confirmationModalActions}>
              <Button onClick={() => setItemToRemove(null)} color="danger" ghost>
                <Trans>Anuluj</Trans>
              </Button>
              <Button color="danger" loading={isDeleting} onClick={() => deleteShoppingList()}>
                <Trans>Usuń</Trans>
              </Button>
            </div>
          </div>
        </Modal>
      )}

      {currentShoppingListId && (
        <Modal
          title={t('Lista produktów')}
          onClose={() => setCurrentShoppingListId(null)}
          fullScreen={isMobile}>
          <ShoppingListPositions
            shoppingListId={currentShoppingListId}
            refetchShoppingLists={refetchShoppingLists}
          />
        </Modal>
      )}
    </div>
  );
};

export default ShoppingLists;
