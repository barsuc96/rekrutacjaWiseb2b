// widok listy pozycji

import React, { FC } from 'react';
import classnames from 'classnames';

import { useRWD } from 'hooks';
import { CartPositions } from 'components/containers';
import PositionsMobile from '../PositionsMobile';

import styles from 'theme/pages/Checkout/components/Positions/Positions.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
}

const Positions: FC<IProps> = ({ cartId }) => {
  const { isMobile } = useRWD();

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-Positions'
      )}>
      {isMobile ? (
        <PositionsMobile cartId={cartId} />
      ) : (
        <CartPositions cartId={cartId} variant="noEdit" />
      )}
    </div>
  );
};

export default Positions;
