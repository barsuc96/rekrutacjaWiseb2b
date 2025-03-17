import React, { useState, useEffect } from 'react';
import { useTranslation, Trans } from 'react-i18next';
import classnames from 'classnames';

import { Button, Modal } from 'components/controls';

import styles from 'theme/components/containers/NewVersionPopup/NewVersionPopup.module.scss';

const MANIFEST = '/asset-manifest.json';

const NewVersionPopup: React.FC = () => {
  const { t } = useTranslation();

  // czy jest widoczny popup
  const [open, setOpen] = useState(false);

  const getLatestVersion = async () => {
    const response = await fetch(MANIFEST);
    return await response.text();
  };

  useEffect(() => {
    const init = async () => {
      try {
        const currentVersion = localStorage.getItem('tend-version');
        const latestVersion = await getLatestVersion();

        if (currentVersion && currentVersion !== latestVersion) {
          setOpen(true);
        }
      } catch (ex) {
        // log somewhere
      }
    };

    if (process.env.NODE_ENV === 'production') {
      init();
    }
  }, []);

  const handleNewVersionReload = async () => {
    try {
      const latestVersion = await getLatestVersion();
      localStorage.setItem('tend-version', latestVersion);
    } catch (ex) {
      // log to sentry / or something
    } finally {
      window.location.reload();
    }
  };

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-NewVersionPopup'
      )}>
      {open && (
        <Modal title={t('Nowa wersja jest dostępna!')}>
          <div className={styles.confirmationModal}>
            <p>
              <Trans>
                Zaktualizowaliśmy stronę internetową. Aby zastosować zmiany, wymagane jest
                przeładowanie.
              </Trans>
            </p>
          </div>

          <div className={styles.confirmationModalActions}>
            <Button color="primary" onClick={() => handleNewVersionReload()}>
              <Trans>Przeładuj</Trans>
            </Button>

            <Button ghost color="secondary" onClick={() => setOpen(false)}>
              <Trans>Anuluj</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default NewVersionPopup;
