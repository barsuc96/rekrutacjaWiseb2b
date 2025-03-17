// komponent wizualizujący dostępność produktu

import React, { FC } from 'react';
import classnames from 'classnames';
import { BoxSeam } from 'react-bootstrap-icons';

import { IStock } from 'api/types';

import styles from 'theme/components/controls/Availability/Availability.module.scss';

// typ danych wejściowych
interface IProps {
  stock: IStock;
}

const Availability: FC<IProps> = ({ stock }) => (
  <div
    className={classnames(styles.componentWrapper, styles[stock.type], 'StylePath-Components-Controls-Availability')}>
    <BoxSeam />
    <span className={styles.label}>{stock.name}</span>
  </div>
);

export default Availability;
