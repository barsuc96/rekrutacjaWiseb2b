import React, { FC, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { Trash } from 'react-bootstrap-icons';

import { useDeleteCmsMedia } from 'api';

import { Modal, Button } from 'components/controls';

import styles from 'theme/pages/Cms/Cms.module.scss';

// typ danych wejściowych
interface IProps {
  refetch: () => void;
  id: number;
}

const DeleteMediaButton: FC<IProps> = ({ id, refetch }) => {
  const { t } = useTranslation();

  const [showModal, setShowModal] = useState(false);

  // kasowanie sekcji
  const { mutate: deleteSection } = useDeleteCmsMedia(id, {
    onSuccess: () => {
      setShowModal(false);
      refetch();
    }
  });

  return (
    <>
      <Trash className={styles.removeButton} onClick={() => setShowModal(true)} />
      {showModal && (
        <Modal title={t('Usuń media')}>
          <h3>
            <Trans>Czy napewno chcesz usunąć zdjęcie?</Trans>
          </h3>
          <p>
            <Trans>Usunięcie pliku spowoduje automatyczne usunięcie odnośników z treści CMS</Trans>
          </p>
          <div className={styles.actions}>
            <Button color="secondary" ghost onClick={() => setShowModal(false)}>
              <Trans>Anuluj</Trans>
            </Button>
            <Button color="primary" onClick={() => deleteSection()}>
              <Trans>Usuń</Trans>
            </Button>
          </div>
        </Modal>
      )}
    </>
  );
};

export default DeleteMediaButton;
