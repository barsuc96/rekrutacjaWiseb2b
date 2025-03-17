// lista pozycji listy zakupowej

import React, { FC, useMemo, useState } from 'react';
import { Checkbox as MuiCheckbox } from '@mui/material';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import {
  useGetShoppingListPositions,
  useDeleteShoppingListPosition,
  usePutShoppingListPosition,
  usePostShoppingListPositionQuantityDecrement,
  usePostShoppingListPositionQuantityIncrement,
  usePostShoppingListExport
} from 'api';
import { IShoppingListPositionListItem, IShoppingListPositionsRequest, IUnit } from 'api/types';
import { Button, Checkbox, Counter, Modal, Select } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';
import { AddPositionsToCartSelect } from 'components/containers';

import { FileExportIcon, TrashIcon, ArrowDropdown } from 'assets/icons';
import styles from 'theme/pages/ShoppingLists/components/ShoppingListPositions/ShoppingListPositions.module.scss';

// typ danych wejściowych
interface IProps {
  shoppingListId: number;
  refetchShoppingLists: () => void;
}

type IExportFileType = 'CSV' | 'XLS';

const ShoppingListPositions: FC<IProps> = ({ shoppingListId, refetchShoppingLists }) => {
  const { t } = useTranslation();

  // parametry zapytania do API o pozycje listy zakupowej
  const [queryParams, setQueryParams] = useState<IShoppingListPositionsRequest>({
    page: 1,
    limit: 20
  });

  // zaznaczone pozycje
  const [checkedItemIds, setCheckedItemIds] = useState<number[]>([]);

  // pozycja wybrana do usunięcia
  const [itemToDelete, setItemToDelete] = useState<IShoppingListPositionListItem | null>(null);

  // IDlki aktualizujących się pozycji
  const [updatingPositionIds, setUpdatingPositionIds] = useState<number[]>([]);

  // wybrany rodzaj exportu
  const [exportFileType, setExportFileType] = useState<IExportFileType>('XLS');

  // pozycje listy zakupowej
  const { data: shoppingListPositions, refetch: refetchShoppingListPositions } =
    useGetShoppingListPositions(shoppingListId, queryParams);

  // skasowanie pozycji z listy zakupowej
  const { mutate: deleteShoppingListPosition, isLoading: isRemoving } =
    useDeleteShoppingListPosition(shoppingListId, itemToDelete?.id || 0, {
      onSuccess: () => {
        setItemToDelete(null);
        refetchShoppingListPositions();
        refetchShoppingLists();
      }
    });

  // skasowanie pozycji z listy zakupowej
  const { mutate: exportShoppingList, isLoading: isExporting } = usePostShoppingListExport(
    shoppingListId,
    {
      onSuccess: (data) => {
        const a = document.createElement('a');
        a.download = data.file_name;
        a.href = `data:application/octet-stream;base64,${data.content}`;
        a.click();
      }
    }
  );

  // aktualizacja pozycji
  const { mutate: updatePosition } = usePutShoppingListPosition(shoppingListId, {
    onSuccess: () => {
      refetchShoppingListPositions();
    },
    onSettled: (data, error, params) => {
      setUpdatingPositionIds((prevState) => prevState.filter((item) => item !== params.positionId));
    }
  });

  // zwiększenie ilości
  const { mutate: increaseQuantity } = usePostShoppingListPositionQuantityIncrement(
    shoppingListId,
    {
      onSuccess: () => {
        refetchShoppingListPositions();
      },
      onSettled: (data, error, params) => {
        setUpdatingPositionIds((prevState) =>
          prevState.filter((item) => item !== params.positionId)
        );
      }
    }
  );

  // zmniejszenie ilości
  const { mutate: decreaseQuantity } = usePostShoppingListPositionQuantityDecrement(
    shoppingListId,
    {
      onSuccess: () => {
        refetchShoppingListPositions();
      },
      onSettled: (data, error, params) => {
        setUpdatingPositionIds((prevState) =>
          prevState.filter((item) => item !== params.positionId)
        );
      }
    }
  );

  const handleChangeQuantity = (
    position: IShoppingListPositionListItem,
    newValue: number | null,
    isIncrement?: boolean
  ) => {
    setUpdatingPositionIds((prevState) => [...prevState, position.id]);

    newValue === null
      ? isIncrement
        ? increaseQuantity({
            positionId: position.id
          })
        : decreaseQuantity({
            positionId: position.id
          })
      : updatePosition({
          positionId: position.id,
          quantity: newValue,
          unit_id: position.unit_id
        });
  };

  const handleChangeUnit = (position: IShoppingListPositionListItem, unitId: number) => {
    setUpdatingPositionIds((prevState) => [...prevState, position.id]);

    updatePosition({
      positionId: position.id,
      quantity: position.quantity,
      unit_id: unitId
    });
  };

  const columns: IColumn<IShoppingListPositionListItem>[] = useMemo(
    () => [
      {
        title: (
          <MuiCheckbox
            checked={
              checkedItemIds.length > 0 &&
              checkedItemIds.length === shoppingListPositions?.items.length
            }
            indeterminate={
              checkedItemIds.length > 0 &&
              checkedItemIds.length < (shoppingListPositions?.items || []).length
            }
            onClick={(event) => {
              event.stopPropagation();
              setCheckedItemIds((old) =>
                old.length < (shoppingListPositions?.items || []).length
                  ? (shoppingListPositions?.items || []).map((position) => position.id)
                  : []
              );
            }}
          />
        ),
        key: 'lp',
        align: 'left',
        isMobileHidden: true,
        renderCell: (item, index) => {
          const isChecked = checkedItemIds.includes(item.id);

          return (
            <div className={styles.checkboxWrapper}>
              <Checkbox
                checked={isChecked}
                onClick={() => {
                  setCheckedItemIds((old) =>
                    isChecked ? old.filter((checkbox) => checkbox !== item.id) : [...old, item.id]
                  );
                }}
              />
              <div className={styles.lp}>{index + 1}</div>
            </div>
          );
        },
        width: 86
      },
      {
        title: <Trans>Nazwa produktu</Trans>,
        key: 'name',
        align: 'left',
        renderCell: (item) => {
          return (
            <div className={styles.title}>
              <div className={styles.name}>{item.name}</div>
              <small>{item.index}</small>
            </div>
          );
        }
      },
      {
        title: <Trans>Ilość</Trans>,
        dataIndex: 'quantity',
        align: 'center',
        width: 200,
        renderCell: (item) => {
          return (
            <div className={styles.quantityCellWrapper}>
              <Counter
                onChange={(value) => handleChangeQuantity(item, value)}
                onIncrease={() => handleChangeQuantity(item, null, true)}
                onDecrease={() => handleChangeQuantity(item, null, false)}
                value={item.quantity}
                disabled={updatingPositionIds.some(
                  (updatingPositionId) => item.id === updatingPositionId
                )}
              />
            </div>
          );
        }
      },
      {
        title: <Trans>Jednostka</Trans>,
        key: 'units',
        align: 'center',
        renderCell: (item) => {
          const options = item.units.map((unit) => ({
            value: unit.id,
            label: unit.name,
            item: unit
          }));
          const unit = item.units.find((unit) => item.unit_id === unit.id);

          return (
            <>
              {options.length > 1 ? (
                <Select<IUnit>
                  value={item.unit_id}
                  options={options}
                  variant="small"
                  onChange={(unit) => unit && handleChangeUnit(item, unit?.id)}
                  disabled={updatingPositionIds.some(
                    (updatingPositionId) => item.id === updatingPositionId
                  )}
                />
              ) : (
                <strong>{unit?.name}</strong>
              )}
              {!!unit?.converter_message && (
                <span className={styles.unitConverterMessage}>{unit.converter_message}</span>
              )}
            </>
          );
        }
      },
      {
        title: `${t('Cena netto')}`,
        dataIndex: 'price_net',
        align: 'right',
        renderCell: (item) => {
          const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

          return (
            <div className={styles.productPrice}>
              {unitData?.price_net_formatted} {item.currency}
            </div>
          );
        }
      },
      {
        title: `${t('Cena brutto')}`,
        dataIndex: 'price_gross',
        align: 'right',
        renderCell: (item) => {
          const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

          return (
            <div className={styles.productPrice}>
              {unitData?.price_gross_formatted} {item.currency}
            </div>
          );
        }
      },
      {
        title: `${t('Wartość')}`,
        dataIndex: 'total_price_net',
        align: 'right',
        renderCell: (item) => (
          <div className={styles.productTotalPrice}>
            <div>
              {item.total_price_gross_formatted} {item.currency}{' '}
              <span className={styles.suffix}>
                <Trans>brutto</Trans>
              </span>
            </div>
            <div className={styles.small}>
              {item.total_price_net_formatted} {item.currency}{' '}
              <span className={styles.suffix}>
                <Trans>netto</Trans>
              </span>
            </div>
          </div>
        )
      },
      {
        title: <Trans>Usuń</Trans>,
        key: 'delete',
        align: 'center',
        renderCell: (item) => {
          return (
            <TrashIcon
              className={styles.deleteButton}
              onClick={() => {
                setItemToDelete(item);
              }}
            />
          );
        }
      }
    ],
    [checkedItemIds, setCheckedItemIds, updatingPositionIds]
  );

  return (
    <>
      <div
        className={classnames(
          styles.wrapperComponent,
          'StylePath-Pages-ShoppingLists-components-ShoppingListPositions'
        )}>
        <div className={styles.actionWrapper}>
          <ArrowDropdown className={styles.arrow} />
          <div className={styles.actionSelectWrapper}>
            <AddPositionsToCartSelect
              positions={
                shoppingListPositions?.items
                  .filter((item) => checkedItemIds.includes(item.id))
                  .map((item) => ({
                    product_id: item.product_id,
                    quantity: item.quantity,
                    unit_id: item.unit_id
                  })) || []
              }
              onSuccess={() => setCheckedItemIds([])}
            />
          </div>

          <button
            className={styles.exportButton}
            onClick={() => exportShoppingList({ export_type: exportFileType })}
            disabled={isExporting}>
            <FileExportIcon />
            <Trans>Exportuj</Trans>
          </button>

          <Select<{ file_type: IExportFileType }>
            onChange={(item) => item && setExportFileType(item?.file_type)}
            options={[
              {
                value: 'XLS',
                label: 'XLS',
                item: { file_type: 'XLS' }
              },
              {
                value: 'CSV',
                label: 'CSV',
                item: { file_type: 'CSV' }
              }
            ]}
            value={exportFileType}
          />
        </div>

        <Table<IShoppingListPositionListItem>
          columns={columns}
          dataSource={shoppingListPositions?.items || []}
          rowKey="id"
          pagination={{
            page: queryParams.page || 1,
            pagesCount: shoppingListPositions?.total_pages || 1,
            onChange: (page) => setQueryParams((prevState) => ({ ...prevState, page }))
          }}
        />
      </div>

      {itemToDelete && (
        <Modal title={t('Potwierdzenie usunięcia')} onClose={() => setItemToDelete(null)}>
          <div className={styles.confirmationModal}>
            <Trans>Czy na pewno chcesz usunąć produkt?</Trans>
            <div className={styles.confirmationModalActions}>
              <Button onClick={() => setItemToDelete(null)} color="danger" ghost>
                <Trans>Anuluj</Trans>
              </Button>

              <Button
                color="danger"
                loading={isRemoving}
                onClick={() => deleteShoppingListPosition()}>
                <Trans>Usuń</Trans>
              </Button>
            </div>
          </div>
        </Modal>
      )}
    </>
  );
};

export default ShoppingListPositions;
