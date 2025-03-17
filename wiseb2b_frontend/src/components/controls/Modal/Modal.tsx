// komponent modal'a

import React, { PropsWithChildren, FC } from 'react';
import MuiModal from '@mui/material/Modal';
import { X } from 'react-bootstrap-icons';
import classnames from 'classnames';

import styles from 'theme/components/controls/Modal/Modal.module.scss';

// typ danych wejściowych
interface IProps {
  title: string;
  fullScreen?: boolean;
  fullWidth?: boolean;
  onClose?: () => void;
}

const Modal: FC<PropsWithChildren<IProps>> = ({
  title,
  onClose,
  children,
  fullScreen,
  fullWidth
}) => {
  return (
    <MuiModal open className={'StylePath-Components-Controls-Modal'}>
      <div
        className={classnames(
          styles.wrapperComponent,
          { [styles.fullScreen]: fullScreen },
          { [styles.fullWidth]: fullWidth }
        )}>
        <div className={styles.header}>
          <div className={styles.title}> {title} </div>
          {!!onClose && (
            <div className={styles.close}>
              <X onClick={onClose} />
            </div>
          )}
        </div>
        <div> {children}</div>
      </div>
    </MuiModal>
  );
};

export default Modal;
