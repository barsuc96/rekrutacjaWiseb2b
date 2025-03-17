// kasowanie exportu

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { useDeleteExport } from 'api';
import { Button } from 'components/controls';

import styles from 'theme/pages/Export/components/RemoveExport/RemoveExport.module.scss';

// typ danych wejściowych
interface IProps {
  data: {
    id: number;
    name: string;
  };
  onCancel: () => void;
  onSuccess: () => void;
}

const RemoveExport: FC<IProps> = ({ data, onCancel, onSuccess }) => {
  const { mutate: deleteExport, isLoading: isDeleting } = useDeleteExport(data.id, {
    onSuccess: () => {
      onSuccess();
      onCancel();
    }
  });

  return (
    <div className={classnames(
      styles.wrapperComponent,
      'StylePath-Pages-Export-components-RemoveExport'
    )}>
      <span>
        <Trans>Czy napewno usunąć ustawienia exportu o nazwie</Trans> <strong>{data.name}</strong>
      </span>

      <div className={styles.actions}>
        <Button onClick={onCancel} color="danger" ghost>
          <Trans>Anuluj</Trans>
        </Button>
        <Button onClick={() => deleteExport()} loading={isDeleting} color="danger">
          <Trans>Usuń</Trans>
        </Button>
      </div>
    </div>
  );
};

export default RemoveExport;
