// nagłówek stron w dashboard

import React, { FC, ReactNode } from 'react';
import classnames from 'classnames';
import { ArrowLeft } from 'react-bootstrap-icons';

import { reduxActions, useDispatch } from 'store';

import styles from 'theme/components/controls/PageTitle/PageTitle.module.scss';

// typ danych wejściowych
interface IProps {
  title: ReactNode;
}

const PageTitle: FC<IProps> = ({ title }) => {
  const dispatch = useDispatch();

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Components-Controls-PageTitle')}>
      <ArrowLeft
        className={styles.backArrow}
        onClick={() => dispatch(reduxActions.setIsMobileMenu(true))}
      />
      <div className={styles.title}>{title}</div>
    </div>
  );
};

export default PageTitle;
