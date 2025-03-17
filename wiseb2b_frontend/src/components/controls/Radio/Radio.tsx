// komponent pojedynczego wyboru

import React, { FC } from 'react';
import { Radio as RadioMui } from '@mui/material';
import classnames from 'classnames';

import styles from 'theme/components/controls/Radio/Radio.module.scss';

// typ danych wejściowych
interface IProps {
  checked: boolean;
  onClick?: () => void;
}

const Radio: FC<IProps> = ({ checked, onClick }) => (
  <span className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-Checkbox')}>
    <RadioMui
      className={classnames(styles.radio, {
        [styles.checked]: checked
      })}
      checked={checked}
      onClick={onClick}
    />
  </span>
);

export default Radio;
