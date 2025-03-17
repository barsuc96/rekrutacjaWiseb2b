// widok pozycji w wersji view

import React from 'react';
import { Trans } from 'react-i18next';

import { ICartPositionListItem } from 'api/types';
import { IColumn } from 'components/controls/Table';
import { Link } from 'components/controls';

import styles from 'theme/components/containers/CartPositions/CartPositions.module.scss';

export const getViewColumns = (): IColumn<ICartPositionListItem>[] => [
  {
    title: <Trans>LP</Trans>,
    key: 'lp',
    align: 'center',
    renderCell: (item, index) => index + 1,
    width: 50
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
    title: <Trans>Produkt</Trans>,
    dataIndex: 'name',
    align: 'left',
    renderCell: (item) => (
      <div className={styles.productInfo}>
        <Link to={`/${item.url_link}`}>{item.name}</Link>
        <div className={styles.grey}>{item.index}</div>
      </div>
    )
  },
  {
    title: <Trans>Ilość</Trans>,
    dataIndex: 'quantity',
    align: 'center',
    renderCell: (item) => <strong>{item.quantity}</strong>
  },
  {
    title: <Trans>Jednostka</Trans>,
    dataIndex: 'unit_id',
    align: 'center',
    renderCell: (item) => {
      const unitData = item.units.find((unit) => unit.unit_id === item.unit_id);

      return (
        <span className={styles.productUnit}>
          <strong>{unitData?.name}</strong>
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
  }
];
