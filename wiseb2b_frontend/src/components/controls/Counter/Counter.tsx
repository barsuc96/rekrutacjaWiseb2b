// typ danych wejściowych

import React, { useEffect, useState, FC } from 'react';
import { Plus, Dash } from 'react-bootstrap-icons';
import classNames from 'classnames';

import styles from 'theme/components/controls/Counter/Counter.module.scss';

// typ danych wejściowych
interface IProps {
  value: number;
  onChange: (value: number) => void;
  onChangeValue?: (isChanged: boolean, newValue?: number) => void;
  onDecrease: () => void;
  onIncrease: () => void;
  disabled?: boolean;
  className?: string;
}

const Counter: FC<IProps> = ({
  value,
  onChange,
  onChangeValue,
  onDecrease,
  onIncrease,
  disabled,
  className
}) => {
  // aktualna wartość
  const [currentValue, setCurrentValue] = useState(value.toString());

  // naspisanie aktualnej wartości przy zmianie wartości z props'ów
  useEffect(() => {
    if (!disabled) {
      setCurrentValue(value.toString());
      onChangeValue?.(false);
    }
  }, [value, disabled]);

  // funkcja walidująca wprowadzoną ręcznie ilość
  const manualUpdateQuantity = (newValue: string) => {
    const newValueNumber = parseInt(newValue);
    isNaN(newValueNumber)
      ? (setCurrentValue(value.toString()), onChangeValue?.(false))
      : (setCurrentValue(newValueNumber.toString()),
        newValueNumber !== value && onChange(newValueNumber));
  };

  return (
    <div className={classNames(styles.wrapperComponent, className, 'StylePath-Components-Controls-Counter')}>
      <button className={styles.button} type="button" onClick={onDecrease} disabled={disabled || value <= 0}>
        <Dash />
      </button>

      <input
        className={styles.input}
        value={currentValue}
        onChange={(event) => {
          setCurrentValue(event.target.value);
          onChangeValue?.(
            event.target.value !== value.toString(),
            event.target.value !== value.toString() ? parseInt(event.target.value) : undefined
          );
        }}
        onBlur={() => manualUpdateQuantity(currentValue)}
        onKeyUp={(event) => event.key === 'Enter' && manualUpdateQuantity(currentValue)}
        disabled={disabled}
        onClick={(event) => event.currentTarget.focus()}
      />

      <button className={styles.button} type="button" onClick={onIncrease} disabled={disabled}>
        <Plus />
      </button>
    </div>
  );
};

export default Counter;
