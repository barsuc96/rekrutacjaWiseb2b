// widok pozycji w wersji default

import React from 'react';
import classnames from 'classnames';
import { Trash, JournalText } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';
import { Menu, MenuItem } from '@mui/material';

import { IColumn } from 'components/controls/Table';
import { ICartPositionListItem, IPositionUnit } from 'api/types';
import { Availability, Checkbox, Select, Link } from 'components/controls';

import QuantityWrapper from './components/QuantityWrapper';

import styles from 'theme/components/containers/CartPositions/CartPositions.module.scss';

export const getEditColumns = ({
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
  cartPositions
}: {
  cartId: number;
  onChange?: () => void;
  queryParams: object;
  checkedItemIds: number[];
  setCheckedItemIds: (items: number[]) => void;
  updatingPositionIds: number[];
  handleChangeUnit: (unitId: number, position: ICartPositionListItem) => void;
  setItemsToShoppingList: (items: ICartPositionListItem[]) => void;
  setItemsToRemove: (items: ICartPositionListItem[]) => void;
  menuAnchor: HTMLButtonElement | null;
  setMenuAnchor: (anchor: HTMLButtonElement | null) => void;
  cartPositions: ICartPositionListItem[];
}): IColumn<ICartPositionListItem>[] => [
  {
    title: (
      <>
        <button
          disabled={checkedItemIds.length === 0}
          className={classnames(styles.dropdownButton, { [styles.open]: !!menuAnchor })}
          onClick={(event) => setMenuAnchor(event.currentTarget)}>
          <Checkbox
            checked={checkedItemIds.length > 0 && checkedItemIds.length === cartPositions.length}
            indeterminate={
              checkedItemIds.length > 0 && checkedItemIds.length < cartPositions.length
            }
            onClick={(event) => {
              event.stopPropagation();
              setCheckedItemIds(
                checkedItemIds.length < cartPositions.length
                  ? cartPositions.map((position) => position.id)
                  : []
              );
            }}
          />
        </button>

        <Menu
          className={styles.menu}
          anchorEl={menuAnchor}
          open={!!menuAnchor}
          onClose={() => setMenuAnchor(null)}>
          <MenuItem
            className={styles.menuItem}
            onClick={() => {
              setItemsToRemove(
                cartPositions.filter((position) => checkedItemIds.includes(position.id))
              );
              setMenuAnchor(null);
            }}>
            <Trash /> <Trans>Usuń</Trans>
          </MenuItem>
          <MenuItem
            className={styles.menuItem}
            onClick={() => {
              setItemsToShoppingList(
                cartPositions.filter((position) => checkedItemIds.includes(position.id))
              );
              setMenuAnchor(null);
            }}>
            <JournalText /> <Trans>Dodaj do listy</Trans>
          </MenuItem>
        </Menu>
      </>
    ),
    key: 'checkbox',
    align: 'left',
    renderCell: (item) => {
      const isChecked = checkedItemIds.includes(item.id);

      return (
        <Checkbox
          checked={isChecked}
          onClick={() =>
            setCheckedItemIds(
              isChecked
                ? checkedItemIds.filter((checkbox) => checkbox !== item.id)
                : [...checkedItemIds, item.id]
            )
          }
        />
      );
    },
    width: 100
  },
  {
    title: '',
    key: 'product-image',
    renderCell: (item) => (
      <div className={styles.productThumb}>
        <Link to={`/${item.url_link}`}>
          <img src={item.image[0]?.thumb} alt={item.name} />
        </Link>
      </div>
    ),
    width: 78
  },
  {
    title: <Trans>Nazwa produktu</Trans>,
    dataIndex: 'name',
    align: 'left',
    renderCell: (item) => (
      <div className={styles.productInfo}>
        <Link to={`/${item.url_link}`}>{item.name}</Link>
        <div className={styles.grey}>{item.index}</div>
        <Availability stock={item.stock} />
      </div>
    )
  },
  {
    title: <Trans>Ilość</Trans>,
    dataIndex: 'quantity',
    align: 'center',
    width: 150,
    renderCell: (item) => {
      const options = item.units.map((unit) => ({
        value: unit.unit_id,
        label: unit.name,
        item: unit
      }));
      const unit = item.units.find((unit) => item.unit_id === unit.unit_id);

      return (
        <div className={styles.quantityCellWrapper}>
          <QuantityWrapper
            cartId={cartId}
            item={item}
            onChange={onChange}
            queryParams={queryParams}
          />
          {options.length > 1 ? (
            <Select<IPositionUnit>
              value={item.unit_id}
              options={options}
              variant="small"
              onChange={(unit) => handleChangeUnit(unit?.unit_id || 0, item)}
              disabled={updatingPositionIds.some(
                (updatingPosition) => item.unit_id === updatingPosition
              )}
            />
          ) : (
            <div>
              <Trans>Jednostka</Trans>: <strong>{unit?.name}</strong>
            </div>
          )}
        </div>
      );
    }
  },
  {
    title: <Trans>Jednostka</Trans>,
    dataIndex: 'unit_id',
    align: 'center',
    renderCell: (item) => {
      const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

      return (
        <span className={styles.productUnit}>
          <strong>{item.quantity}</strong> x <strong>{unitData?.name}</strong>
          {!!unitData?.converter_message && (
            <span className={styles.descriptionLine}>({unitData.converter_message})</span>
          )}
        </span>
      );
    }
  },
  {
    title: <Trans>Cena netto</Trans>,
    dataIndex: 'price_net',
    sortBy: 'price_net',
    align: 'right',
    renderCell: (item) => {
      const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

      return (
        <div className={styles.productPrice}>
          {item.price_net_formatted} {item.currency}
          <div className={styles.small}>
            {item.unit_price_net_formatted} {item.currency} ({unitData?.name})
          </div>
        </div>
      );
    }
  },
  {
    title: <Trans>Cena brutto</Trans>,
    dataIndex: 'price_gross',
    sortBy: 'price_gross',
    align: 'right',
    renderCell: (item) => {
      const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

      return (
        <div className={styles.productPrice}>
          {item.price_gross_formatted} {item.currency}
          <div className={styles.small}>
            {item.unit_price_gross_formatted} {item.currency} ({unitData?.name})
          </div>
        </div>
      );
    }
  },
  {
    title: <Trans>Wartość</Trans>,
    dataIndex: 'total_price_net',
    sortBy: 'total_price_net',
    align: 'right',
    renderCell: (item) => (
      <div className={styles.productTotalPrice}>
        {item.total_price_gross_formatted} {item.currency}{' '}
        <span className={styles.suffix}>
          <Trans>brutto</Trans>
        </span>
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
    title: <Trans>Opcje</Trans>,
    key: 'actions',
    align: 'center',
    renderCell: (item) => (
      <div className={styles.actionsWrapper}>
        <JournalText onClick={() => setItemsToShoppingList([item])} />
        <Trash onClick={() => setItemsToRemove([item])} />
      </div>
    )
  }
];
