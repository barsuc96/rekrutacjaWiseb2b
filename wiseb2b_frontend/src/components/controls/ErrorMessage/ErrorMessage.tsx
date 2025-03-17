// tekst z błędem wyświetlany w formularzach

import React, { FC, PropsWithChildren } from 'react';
import classnames from 'classnames';

import styles from 'theme/components/controls/ErrorMessage/ErrorMessage.module.scss';

const ErrorMessage: FC<PropsWithChildren> = ({ children }) => {
  return <div className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-ErrorMessage')}>{ children }</div>
};

export default ErrorMessage;
