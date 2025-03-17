// komponent przycisku

import React, { FC, MouseEventHandler, PropsWithChildren } from 'react';
import classnames from 'classnames';
import { CircularProgress } from '@mui/material';

import styles from 'theme/components/controls/Button/Button.module.scss';

// typ danych wejściowych
export interface IProps {
  onClick?: MouseEventHandler<HTMLButtonElement>;
  loading?: boolean;
  disabled?: boolean;
  htmlType?: 'submit' | 'button';
  size?: 'default' | 'large';
  color?: 'primary' | 'secondary' | 'danger' | 'gray';
  ghost?: boolean;
}

const Button: FC<IProps & PropsWithChildren> = ({
  children,
  onClick,
  htmlType = 'button',
  loading,
  disabled,
  size = 'default',
  color = 'primary',
  ghost
}) => {
  return (
    <button
      onClick={onClick}
      type={htmlType}
      disabled={loading || disabled}
      className={classnames(
        styles.button,
        styles[size],
        styles[color],
        {
          [styles.disabled]: loading || disabled,
          [styles.loading]: loading,
          [styles.ghost]: ghost
        },
        'StylePath-Components-Controls-Button'
      )}>
      <span className={styles.label}>{children}</span>
      {loading && (
        <CircularProgress
          className={classnames(styles.progress, { [styles.ghost]: ghost }, styles[color])}
        />
      )}
    </button>
  );
};

export default Button;
