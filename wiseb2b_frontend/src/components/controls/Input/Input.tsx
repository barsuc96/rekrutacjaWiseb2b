// komponent inputa

import React, { FC } from 'react';
import classnames from 'classnames';

import { ErrorMessage } from 'components/controls';

import styles from 'theme/components/controls/Input/Input.module.scss';

// typ danych wejściowych
export interface IProps {
  type?: 'password' | 'text';
  value?: string;
  onChange?: (value: string) => void;
  error?: string;
  placeholder?: string;
  disabled?: boolean;
}

const Input: FC<IProps> = ({ type, value, onChange, error, placeholder, disabled }) => {
  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-Input')}>
      <input
        className={classnames({ [styles.error]: !!error })}
        type={type}
        value={value}
        onChange={(event) => onChange && onChange(event.target.value)}
        placeholder={placeholder}
        onClick={(event) => event.currentTarget.focus()}
        disabled={disabled}
      />

      {error && <ErrorMessage>{error}</ErrorMessage>}
    </div>
  );
};

export default Input;
