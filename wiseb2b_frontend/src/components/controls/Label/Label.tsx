import React, { FC } from 'react';
import classnames from 'classnames';

import { IProductLabel } from 'api/types';

import styles from 'theme/components/controls/Label/Label.module.scss';

interface IProps {
  label: IProductLabel;
}

const Label: FC<IProps> = ({ label }) => (
  <span
    className={classnames(
      styles.wrapperComponent,
      styles[label.type.toLowerCase()],
      'StylePath-Components-Controls-Label'
    )}>
    {label.name}
  </span>
);

export default Label;
