import React, { FC } from 'react';
import { Trash } from 'react-bootstrap-icons';

import { useDeleteCmsArticle } from 'api';

import styles from 'theme/pages/Cms/Cms.module.scss';

// typ danych wejściowych
interface IProps {
  refetch: () => void;
  articleId: number;
}

const DeleteSectionButton: FC<IProps> = ({ articleId, refetch }) => {
  // kasowanie sekcji
  const { mutate: deleteArticle } = useDeleteCmsArticle(articleId, {
    onSuccess: () => {
      refetch();
    }
  });

  return <Trash className={styles.removeButton} onClick={() => deleteArticle()} />;
};

export default DeleteSectionButton;
