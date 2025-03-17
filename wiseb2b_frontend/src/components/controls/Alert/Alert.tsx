// alert/wiadomość w różnej wersji kolotystycznej

import React, { FC, PropsWithChildren, ReactElement } from 'react';
import { Alert as AlertMui } from '@mui/material';
import classnames from 'classnames';

import styles from 'theme/components/controls/Alert/Alert.module.scss';

// typ danych wejściowych
interface IProps {
  type: 'error' | 'warning' | 'success';
  icon?: ReactElement;
}
const Alert: FC<PropsWithChildren & IProps> = ({ type, children, icon }) => {
  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-Alert')}>
      <AlertMui 
        severity={type} 
        icon={icon} 
        className={classnames(styles['MuiPaper-root'] ,styles[`message-${type}`])}
      >
        {children}
      </AlertMui>
    </div>
  );
};

export default Alert;
