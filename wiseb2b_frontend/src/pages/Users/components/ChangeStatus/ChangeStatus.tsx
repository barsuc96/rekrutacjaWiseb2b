// komponent zmiany statusu użytkownika

import React, { FC } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { usePutUserChangeStatus } from 'api';
import { IUserListItem } from 'api/types';
import { Button } from 'components/controls';

import styles from 'theme/pages/Users/components/ChangeStatus/ChangeStatus.module.scss';

// typ danych wejściowych
interface IProps {
  user: IUserListItem;
  onCancel: () => void;
  onSuccess: () => void;
}

const ChangeStatus: FC<IProps> = ({ user, onCancel, onSuccess }) => {
  const { t } = useTranslation();

  // wysłanie zapytania do api o zmianę statusu
  const { mutate: changeStatus, isLoading: isChangingStatus } = usePutUserChangeStatus(user.id, {
    onSuccess: () => {
      onSuccess();
      onCancel();
    }
  });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Pages-Users-components-ChangeStatus'
      )}>
      <Trans>Czy napewno {user.status ? 'dezaktywować' : 'aktywować'} użytkownika</Trans>{' '}
      <strong>
        {user.first_name} {user.last_name}
      </strong>
      ?
      <div className={styles.actions}>
        <Button onClick={onCancel} ghost color={user.status ? 'danger' : 'primary'}>
          <Trans>Anuluj</Trans>
        </Button>
        <Button
          loading={isChangingStatus}
          color={user.status ? 'danger' : 'primary'}
          onClick={() =>
            changeStatus({
              is_active: !user.status
            })
          }>
          {user.status ? t('Deaktywuj') : t('Aktywuj')}
        </Button>
      </div>
    </div>
  );
};

export default ChangeStatus;
