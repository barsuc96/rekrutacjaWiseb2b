// spinner

import React from 'react';
import { CircularProgress } from '@mui/material';
import classnames from 'classnames';

import styles from 'theme/components/controls/Loader/Loader.module.scss';

const Loader = () => {
  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-Loader')}>
      <CircularProgress className={styles.spinner} />
    </div>
  );
};

export default Loader;
