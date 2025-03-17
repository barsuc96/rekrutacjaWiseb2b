// strona regulaminu

import React from 'react';
import classnames from 'classnames';

import wip from 'assets/images/wip.jpg'
import styles from 'theme/pages/WorkInProgress/WorkInProgress.module.scss';

const WorkInProgress = () => {

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-WorkInProgress')}>
      <img src={wip} />
    </div>
  );
};

export default WorkInProgress;
