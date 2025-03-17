// strona dashboardu

import React from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { PageTitle } from 'components/controls';

import styles from 'theme/pages/Dashboard/Dashboard.module.scss';

const PanelDashboard = () => {
  const { t } = useTranslation();

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Dashboard')}>
      <PageTitle title={t('Dashboard')} />
    </div>
  );
};

export default PanelDashboard;
