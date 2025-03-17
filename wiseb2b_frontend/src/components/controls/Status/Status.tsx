// status płatności, dostaw

import React, { FC } from 'react';
import classnames from 'classnames';

import styles from 'theme/components/controls/Status/Status.module.scss';

// typ danych wejściowych
interface IProps {
  icon: string;
  message: string;
  color?: string;
}

const Status: FC<IProps> = ({ icon, message, color }) => {
  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Controls-SearchInput'
      )}
      style={{ borderColor: color, color: color }}>
      <img src={icon} alt="" />
      <span className={styles.message}>{message}</span>
    </div>
  );
};

export default Status;
