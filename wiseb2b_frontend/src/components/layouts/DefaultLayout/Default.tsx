// domyślny layout

import React, { FC, PropsWithChildren } from 'react';
import { Outlet } from 'react-router';
import classnames from 'classnames';

import { Container } from 'components/controls';

import styles from 'theme/components/layouts/DefaultLayout/DefaultLayout.module.scss';
import logo from 'assets/logo.svg';

const Default: FC<PropsWithChildren> = ({ children }) => (
  <div className={classnames(styles.wrapperComponent, 'StylePath-Layouts-DefaultLayout')}>
    <img src={logo} alt="logo" className={styles.logo} />

    <Container>
      <Outlet />
      {children}
    </Container>
  </div>
);

export default Default;
