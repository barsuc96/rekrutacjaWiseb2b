// overlay wyświetlany podczas pobieraniu profilu użytkownika

import React from 'react';
import { useTranslation } from 'react-i18next';
import { CircularProgress } from '@mui/material';
import classnames from 'classnames';

import styles from 'theme/components/containers/ProfileLoading/ProfileLoading.module.scss';

const ProfileLoading = () => {
  const { t } = useTranslation();
  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ProfileLoading'
      )}>
      <CircularProgress className={styles.progress} />
      {t('Pobieranie danych użytkownika')}
    </div>
  );
};

export default ProfileLoading;
