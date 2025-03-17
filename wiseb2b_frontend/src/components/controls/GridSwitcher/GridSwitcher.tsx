// przełącznik widoku list

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';
import { Grid3x3GapFill } from 'react-bootstrap-icons';

import styles from 'theme/components/controls/GridSwitcher/GridSwitcher.module.scss';
import { ReactComponent as IconGridLines } from 'assets/grid-switcher-lines.svg';

// typ danych wejściowych
export interface IProps {
  type: 'grid' | 'line';
  onChange: (type: IProps['type']) => void;
}

const GridSwitcher: FC<IProps> = ({ type, onChange }) => {
  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Components-Controls-GridSwitcher')}>
      <span className={styles.name}>
        <Trans>Widok</Trans>:{' '}
      </span>
      <Grid3x3GapFill
        className={classnames(styles.gridIcon, { [styles.active]: type === 'grid' })}
        onClick={() => type !== 'grid' && onChange('grid')}
      />
      <IconGridLines
        className={classnames(styles.gridIcon, { [styles.active]: type === 'line' })}
        onClick={() => type !== 'line' && onChange('line')}
      />
    </div>
  );
};

export default GridSwitcher;
