// komponent Checkbox
import React, { FC } from 'react';
import { Checkbox as CheckboxMui } from '@mui/material';
import classnames from 'classnames';

import styles from 'theme/components/controls/Checkbox/Checkbox.module.scss';

// typ danych wejściowych
interface IProps {
  checked: boolean;
  disabled?: boolean;
  indeterminate?: boolean;
  onClick?: (event: React.MouseEvent<HTMLElement>) => void;
}

const Checkbox: FC<IProps> = ({ checked, disabled, onClick, indeterminate }) => (
  <span
    className={classnames(
      styles.wrapperComponent,
      { [styles.disabled]: !!disabled },
      'StylePath-Components-Controls-Checkbox'
    )}>
    <CheckboxMui
      className={classnames(styles.checkbox, {
        [styles.checked]: checked,
        [styles.indeterminate]: indeterminate
      })}
      checked={checked}
      indeterminate={indeterminate}
      onClick={onClick}
      disabled={disabled}
    />
  </span>
);

export default Checkbox;
