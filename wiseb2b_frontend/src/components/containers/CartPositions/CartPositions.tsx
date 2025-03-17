// tabelka z pozycjami koszyka

import React, { FC, ReactNode, useEffect, useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import {
  useDeleteCartPositions,
  useGetCartPositions,
  useGetCartsAll,
  usePutCartPositionUnit
} from 'api';
import { ICartPositionListItem } from 'api/types';
import { useNotifications, useRWD } from 'hooks';
import { Button, Modal } from 'components/controls';
import { AddToShoppingListForm } from 'components/containers';
import Table, { ISorter } from 'components/controls/Table';
import MobileCart from './components/MobileCart';
import { getEditColumns } from './columnsEdit';
import { getViewColumns } from './columnsView';
import { getSmallColumns } from './columnsSmall';

import styles from 'theme/components/containers/CartPositions/CartPositions.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
  onChange?: () => void;
  searchKeyword?: string;
  variant?: 'default' | 'noEdit' | 'small';
  noDataPlaceholder?: ReactNode;
}

const CartPositions: FC<IProps> = ({
  cartId,
  onChange,
  searchKeyword = '',
  variant = 'default',
  noDataPlaceholder
}) => {
  const { t } = useTranslation();
  const { showWarningMessage } = useNotifications();
  const { isMobile } = useRWD();

  // parametry zapytania API
  const [queryParams, setQueryParams] = useState({
    page: 1,
    limit: 10,
    search_keyword: searchKeyword
  });

  // element html dropdownu zaznaczenia
  const [menuAnchor, setMenuAnchor] = useState<HTMLButtonElement | null>(null);

  // lista zaznaczonyxh pozycji (ID'ki)
  const [checkedItemIds, setCheckedItemIds] = useState<number[]>([]);

  // lista pozycji do usunięcia
  const [itemsToRemove, setItemsToRemove] = useState<ICartPositionListItem[]>([]);

  // lista pozycji do dodania do listy zakupowej
  const [itemsToShoppingList, setItemsToShoppingList] = useState<ICartPositionListItem[]>([]);

  // lista aktualizowanych pozycji (ID'ki)
  const [updatingPositionIds, setUpdatingPositionIds] = useState<number[]>([]);

  // informacja o sortowaniu listy
  const [sorter, setSorter] = useState<ISorter<ICartPositionListItem>>({
    by: 'name',
    direction: 'asc'
  });

  // refresh pozycji po zmianie koszyków
  useGetCartsAll({
    enabled: false,
    onSuccess: () => refetchCartPositions()
  });

  // pobranie listy pozycji koszyka
  const { data: cartPositionsData, refetch: refetchCartPositions } = useGetCartPositions(
    cartId,
    queryParams,
    {
      enabled: !!cartId
    }
  );

  // skasowanie pozycji z koszyka
  const { mutate: deleteCartPositions, isLoading: isCartPositionsDeleting } =
    useDeleteCartPositions(cartId, {
      onSuccess: () => {
        onChange?.();
        refetchCartPositions();
        setItemsToRemove([]);
        setCheckedItemIds([]);
      }
    });

  // aktualizacja jednostki pozycji
  const { mutate: updatePositionUnit } = usePutCartPositionUnit(cartId);

  // aktualizacja parametrów zapytania do api przy zmianie frazy wyszukiwania
  useEffect(() => {
    setQueryParams((prevState) => ({ ...prevState, search_keyword: searchKeyword }));
  }, [searchKeyword]);

  // funkcja wrapująca aktualizację jednostki
  const handleChangeUnit = async (unitId: number, position: ICartPositionListItem) => {
    setUpdatingPositionIds((prevState) => [...prevState, position.id]);
    updatePositionUnit(
      {
        positionId: position.id,
        unit_id: unitId
      },
      {
        onSuccess: () => {
          onChange?.();
          refetchCartPositions();
        },
        onSettled: () => {
          setUpdatingPositionIds((prevState) => prevState.filter((item) => item !== position.id));
        }
      }
    );
  };

  // wybranie wariantu tabelki
  const columns = useMemo(
    () =>
      variant === 'noEdit'
        ? getViewColumns()
        : variant === 'small'
        ? getSmallColumns({
            cartId,
            onChange,
            queryParams,
            updatingPositionIds,
            setItemsToRemove
          })
        : getEditColumns({
            cartId,
            onChange,
            queryParams,
            checkedItemIds,
            setCheckedItemIds,
            updatingPositionIds,
            handleChangeUnit,
            setItemsToShoppingList,
            setItemsToRemove,
            menuAnchor,
            setMenuAnchor,
            cartPositions: cartPositionsData?.items || []
          }),
    [updatingPositionIds, cartPositionsData, checkedItemIds, menuAnchor, variant]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-CartPositions'
      )}>
      <Table<ICartPositionListItem>
        columns={columns}
        dataSource={cartPositionsData?.items || []}
        rowKey="id"
        sorter={sorter}
        mobileItem={
          variant == 'default'
            ? (item) => (
                <MobileCart
                  cartId={cartId}
                  onChange={onChange}
                  queryParams={queryParams}
                  key={item.id}
                  features={{
                    setItemsToShoppingList: setItemsToShoppingList,
                    setItemsToRemove: setItemsToRemove,
                    updatingPositionIds: updatingPositionIds,
                    handleChangeUnit: handleChangeUnit
                  }}
                  item={item}
                />
              )
            : variant == 'noEdit'
            ? undefined
            : undefined
        }
        onSortChange={(sorter) => {
          setSorter(sorter);
          showWarningMessage('Funkcja niezaimplementowana');
        }}
        pagination={{
          page: queryParams.page,
          pagesCount: cartPositionsData?.total_pages || 1,
          onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
        }}
      />

      {cartPositionsData && cartPositionsData.items.length === 0 && noDataPlaceholder}

      {itemsToRemove.length > 0 && (
        <Modal title={t('Potwierdzenie usunięcia')} onClose={() => setItemsToRemove([])}>
          <div className={styles.confirmationModal}>
            {t('Czy napewno usunąć poniższe produkty z koszyka?')}
            <ul>
              {itemsToRemove.map((item) => (
                <li key={item.id}>
                  <strong> {item.name}</strong>
                </li>
              ))}
            </ul>
            <div className={styles.confirmationModalActions}>
              <Button onClick={() => setItemsToRemove([])} ghost color="secondary">
                {t('Anuluj')}
              </Button>
              <Button
                color="secondary"
                loading={isCartPositionsDeleting}
                onClick={() => {
                  deleteCartPositions({
                    cart_id: cartId,
                    positions: itemsToRemove.map((item) => ({ id: item.id }))
                  });
                }}>
                {t('Usuń')}
              </Button>
            </div>
          </div>
        </Modal>
      )}

      {itemsToShoppingList.length > 0 && (
        <Modal
          fullScreen={isMobile}
          title={t('Dodaj do listy zakupowej')}
          onClose={() => setItemsToShoppingList([])}>
          <AddToShoppingListForm
            items={itemsToShoppingList.map((item) => ({
              product_id: item.product_id,
              unit_id: item.unit_id,
              quantity: item.quantity,
              image: item.image[0]?.thumb,
              title: item.name,
              index: item.index,
              price_net_formatted: item.price_net_formatted,
              price_gross_formatted: item.price_gross_formatted,
              currency: item.currency
            }))}
            onCancel={() => setItemsToShoppingList([])}
            onSuccess={() => setCheckedItemIds([])}
          />
        </Modal>
      )}
    </div>
  );
};

export default CartPositions;
