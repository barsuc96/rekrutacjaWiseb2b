import React, { FC } from 'react';
import { Trash } from 'react-bootstrap-icons';

import { useDeleteCmsSection } from 'api';

import styles from 'theme/pages/Cms/Cms.module.scss';

// typ danych wejściowych
interface IProps {
  refetch: () => void;
  id: number;
}

const DeleteSectionButton: FC<IProps> = ({ id, refetch }) => {
  // kasowanie sekcji
  const { mutate: deleteSection } = useDeleteCmsSection(id, {
    onSuccess: () => {
      refetch();
    }
  });

  return <Trash className={styles.removeButton} onClick={() => deleteSection()} />;
};

export default DeleteSectionButton;
