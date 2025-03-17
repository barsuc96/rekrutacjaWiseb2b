// widok pozycji w wersji small

import React from 'react';
import { Trans } from 'react-i18next';
import { Trash } from 'react-bootstrap-icons';

import { ICartPositionListItem } from 'api/types';
import { IColumn } from 'components/controls/Table';
import { Availability, Link } from 'components/controls';

import QuantityWrapper from './components/QuantityWrapper';

import styles from 'theme/components/containers/CartPositions/CartPositions.module.scss';

export const getSmallColumns = ({
  cartId,
  onChange,
  queryParams,
  setItemsToRemove
}: {
  cartId: number;
  onChange?: () => void;
  queryParams: object;
  updatingPositionIds: number[];
  setItemsToRemove: (items: ICartPositionListItem[]) => void;
}): IColumn<ICartPositionListItem>[] => [
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
    width: 160,
    renderCell: (item) => (
      <div className={styles.quantityCellWrapper}>
        <QuantityWrapper
          cartId={cartId}
          item={item}
          onChange={onChange}
          queryParams={queryParams}
        />
      </div>
    )
  },
  {
    title: <Trans>Wartość</Trans>,
    dataIndex: 'total_price_net',
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
        <Trash onClick={() => setItemsToRemove([item])} />
      </div>
    )
  }
];
