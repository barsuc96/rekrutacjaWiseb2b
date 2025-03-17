// zwijany box

import React, { FC, PropsWithChildren } from 'react';
import classnames from 'classnames';
import { ChevronDown } from 'react-bootstrap-icons';

import { Container } from 'components/controls';

import styles from 'theme/components/controls/Collapse/Collapse.module.scss';

// typ danych wejściowych
interface IProps {
  open: boolean;
  title: string;
  onClick?: () => void;
}
const Collapse: FC<PropsWithChildren<IProps>> = ({ title, children, open, onClick }) => {
  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        { [styles.opened]: open },
        'StylePath-Components-Controls-Collapse'
      )}>
      <div className={styles.header} onClick={onClick}>
        <Container>
          <div className={styles.box}>
            <div className={styles.arrow}>
              <ChevronDown />
            </div>
            <div className={styles.title}>{title}</div>
          </div>
        </Container>
      </div>
      <Container>{open && <div className={styles.content}>{children}</div>}</Container>
    </div>
  );
};

export default Collapse;
