// komponent buttona z rozwijanym menu

import React, { FC, MouseEvent, ReactNode } from 'react';
import { ChevronDown } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { Menu as MenuMui, MenuItem as MenuItemMui } from '@mui/material';

import styles from 'theme/components/controls/DropDown/DropDown.module.scss';

// typ danych wejściowych
interface IProps {
  label: ReactNode;
  items: {
    label: ReactNode;
    onClick?: () => void;
    disabled?: boolean;
  }[];
  disabled?: boolean;
  bordered?: boolean;
  withDropdownIcon?: boolean;
  disableClose?: boolean;
}

const DropDown: FC<IProps> = ({
  label,
  items,
  disabled,
  withDropdownIcon = true,
  bordered,
  disableClose
}) => {
  // element HTML dropdownu (jeśli jest to jest otwarty)
  const [popupAnchor, setPopupAnchor] = React.useState<HTMLElement | null>(null);

  // funkcja obsługująca kliknięcie przycisku
  const handleClick = (event: MouseEvent<HTMLElement>) => {
    setPopupAnchor(event.currentTarget);
  };

  // funkcja zamykająca dropdown
  const handleClose = () => {
    setPopupAnchor(null);
  };

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        { [styles.bordered]: bordered },
        'StylePath-Components-Controls-DropDown'
      )}>
      <button
        className={classnames(styles.button, { [styles.open]: !!popupAnchor })}
        onClick={handleClick}
        disabled={disabled}>
        {label} {bordered && <div className={styles.line} />}{' '}
        {withDropdownIcon && <ChevronDown className={styles.dropdownArrow} />}
      </button>

      <MenuMui
        anchorEl={popupAnchor}
        open={!!popupAnchor}
        onClose={handleClose}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'left'
        }}
        className={styles['menu']}>
        {items.map((item, index) => (
          <MenuItemMui
            className={classnames(styles['menuItem'], { [styles.disabled]: item.disabled })}
            onClick={() => {
              !disableClose && setPopupAnchor(null);
              item.onClick?.();
            }}
            key={index}>
            {item.label}
          </MenuItemMui>
        ))}
      </MenuMui>
    </div>
  );
};

export default DropDown;
