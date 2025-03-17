import classnames from 'classnames';
import { Cart, ChevronDown, JournalText, Trash } from 'react-bootstrap-icons';
import Popover from '@mui/material/Popover';
import React, { useEffect, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { format } from 'date-fns';

import { useDeleteShoppingList, useGetShoppingLists } from 'api';
import { IShoppingListListItem } from 'api/types';
import { Button, Link, Modal, Loader } from 'components/controls';
import { useRWD } from 'hooks';
import { reduxActions, useDispatch } from 'store';
import { ShoppingListPositions } from 'pages/ShoppingLists/components';

import styles from 'theme/components/containers/ShoppingListButton/ShoppingListButton.module.scss';

const ShoppingListModal = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  const [popoverAnchor, setPopoverAnchor] = useState<HTMLDivElement | null>(null);

  const [currentShoppingListId, setCurrentShoppingListId] = useState<number | null>(null);

  // lista zakupowa do usunięcia
  const [itemToRemove, setItemToRemove] = useState<IShoppingListListItem | null>(null);

  // pobranie list zakupowych
  const { data: shoppingListsData, refetch: refetchShoppingLists } = useGetShoppingLists(
    {
      page: 1,
      limit: 9999
    },
    { enabled: false, keepPreviousData: true }
  );

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

  // pobieranie listy zakupowej
  useEffect(() => {
    if (popoverAnchor) {
      refetchShoppingLists();
    }
  }, [popoverAnchor]);

  // ustawienie breadcrumbsów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Listy zakupowe') }
      ])
    );
  }, []);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ShoppingListButton'
      )}>
      <div
        role="button"
        onClick={(e) => setPopoverAnchor(e.currentTarget)}
        className={classnames(styles.link, { [styles.active]: popoverAnchor })}>
        <JournalText /> {t('Listy zakupowe')}
      </div>

      <Popover
        anchorEl={popoverAnchor}
        open={!!popoverAnchor && !isMobile}
        onClose={() => setPopoverAnchor(null)}
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right'
        }}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'right'
        }}>
        <div className={styles.listWrapper}>
          <div className={styles.listTitle}>
            <span>
              <Trans>Listy zakupowe</Trans>
            </span>
            ({shoppingListsData?.items?.length})
          </div>
          {!shoppingListsData && <Loader />}
          <div className={styles.list}>
            {shoppingListsData?.items.map((item) => {
              return (
                <div key={item.id} className={styles.listItem}>
                  <div className={styles.description}>
                    <div>
                      <strong>{item.name}</strong> (<Trans>produktów</Trans> {item.products_count})
                    </div>
                    <div className={styles.datetime}>
                      <Trans>Data utworzenia</Trans>:{' '}
                      {item.create_datetime &&
                        format(new Date(item.create_datetime), 'dd.MM.yyyy (hh:mm)')}
                    </div>
                  </div>

                  <div className={styles.actions}>
                    <button
                      disabled={!item.products_count}
                      className={styles.cart}
                      onClick={() => setCurrentShoppingListId(item.id)}>
                      <Cart /> <ChevronDown />
                    </button>

                    <Trash onClick={() => setItemToRemove(item)} />
                  </div>
                </div>
              );
            })}
          </div>
          <Link
            to="/dashboard/shopping-lists"
            onClick={() => setPopoverAnchor(null)}
            className={styles.seeMore}>
            <Trans>Zobacz więcej</Trans>
          </Link>
        </div>

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
      </Popover>
    </div>
  );
};

export default ShoppingListModal;
