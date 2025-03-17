// widok listy pozycji

import React, { FC } from 'react';
import classnames from 'classnames';

import { useGetCartPositions } from 'api';
import { ProductMobile } from 'components/containers';

import styles from 'theme/pages/Checkout/components/PositionsMobile/PositionsMobile.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
}

const PositionsMobile: FC<IProps> = ({ cartId }) => {
  // pobranie listy pozycji koszyka
  const { data: cartPositionsData } = useGetCartPositions(
    cartId,
    { page: 1, limit: 999 },
    {
      enabled: !!cartId
    }
  );
  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-PositionsMobile'
      )}>
      {cartPositionsData?.items.map((position) => (
        <ProductMobile key={position.id} item={position} />
      ))}
    </div>
  );
};

export default PositionsMobile;
