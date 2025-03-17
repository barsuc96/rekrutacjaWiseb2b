import React, { FC, ReactNode } from 'react';
import { ArrowRight } from 'react-bootstrap-icons';
import classnames from 'classnames';

import styles from 'theme/components/controls/BlockImageLink/BlockImageLink.module.scss';

interface IProps {
  fullHeight?: boolean;
  imageUrl?: string;
  name: ReactNode;
  onClick?: () => void;
}

const BlockImageLink: FC<IProps> = ({ fullHeight = false, onClick, imageUrl, name }) => (
  <div
    className={classnames(
      styles.componentWrapper,
      { [styles.fullHeight]: fullHeight },
      'StylePath-Components-Controls-BlockImageLink'
    )}
    onClick={onClick}>
    <div className={classnames(styles.name)}>
      <span>
        {name} 
      </span>
      <ArrowRight />
    </div>
    {imageUrl && <img className={styles.image} src={imageUrl} />}
  </div>
);

export default BlockImageLink;
