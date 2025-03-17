// przycisk z listą podglądu kkoszyków

import React, { useState } from 'react';
import classnames from 'classnames';
import Popover from '@mui/material/Popover';
import { useTranslation } from 'react-i18next';

import { useRWD } from 'hooks';
import { Login } from 'pages';

import styles from 'theme/components/containers/LoginButton/LoginButton.module.scss';

const ButtonCart = () => {
  const { t } = useTranslation();

  const { isMobile } = useRWD();

  // czy jest ustawiony element HTML popovera (użyte przy pokazywaniu modala)
  const [popoverAnchor, setPopoverAnchor] = useState<HTMLButtonElement | null>(null);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-LoginButton'
      )}>
      <button
        className={styles.authButton}
        onClick={(event) => setPopoverAnchor(event.currentTarget)}>
        {t('Logowanie/Rejestracja')}
      </button>

      <Popover
        anchorEl={popoverAnchor}
        open={!!popoverAnchor && !isMobile}
        onClose={() => setPopoverAnchor(null)}
        transformOrigin={{
          vertical: 'top',
          horizontal: 'right'
        }}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'right'
        }}>
        <Login isPopover />
      </Popover>
    </div>
  );
};

export default ButtonCart;
