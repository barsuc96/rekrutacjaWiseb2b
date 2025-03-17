// zarządznie listą zgód uźytkownika

import React, { FC, useMemo, useState } from 'react';
import { useTranslation, Trans } from 'react-i18next';
import classnames from 'classnames';

import { useSelector } from 'store';
import { useGetUserAgreements, usePutUserAgreement, useGetUserProfile } from 'api';
import { IUserAgreementListItem } from 'api/types';
import { Button, Modal } from 'components/controls';
import Table, { IColumn } from 'components/controls/Table';

import styles from 'theme/components/containers/Agreements/Agreements.module.scss';

// typ danych wejściowych
interface IProps {
  userId: number;
}

const Agreements: FC<IProps> = ({ userId }) => {
  const { t } = useTranslation();
  const { profile } = useSelector((state) => state.auth);

  // odświeżenie danych profilowych
  const { refetch: refetchProfile } = useGetUserProfile({ enabled: false });

  // zgoda do zatwoerdzenia
  const [agreementToChange, setAgreementToChange] = useState<IUserAgreementListItem | null>(null);

  // pobranie listy zgód
  const { data: userAgreementsData, refetch: refetchUserAgreements } = useGetUserAgreements(userId);

  // aktualizacja zgody
  const { mutate: updateAgreement, isLoading: isUpdatingAgreement } = usePutUserAgreement(userId, {
    onSuccess: () => {
      refetchUserAgreements();
      setAgreementToChange(null);
      profile?.id === userId && refetchProfile();
    }
  });

  const columns: IColumn<IUserAgreementListItem>[] = useMemo(
    () => [
      {
        title: <Trans>Adres IP</Trans>,
        dataIndex: 'ip_address'
      },
      {
        title: <Trans>Data operacji</Trans>,
        dataIndex: 'date'
      },
      {
        title: <Trans>Treść zgody</Trans>,
        dataIndex: 'content'
      },
      {
        title: '',
        key: 'actions',
        renderCell: (item) => (
          <div className={styles.tableActions}>
            <Button
              color={item.accepted ? 'danger' : 'primary'}
              onClick={() => setAgreementToChange(item)}>
              {item.accepted ? t('Wycofaj zgodę') : t('Zaakceptuj zgodę')}
            </Button>
            {item.necessary && (
              <span>
                (<Trans>wymagana</Trans>)
              </span>
            )}
          </div>
        )
      }
    ],
    []
  );

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Components-Containers-Agreements')}>
      <Table<IUserAgreementListItem>
        columns={columns}
        dataSource={userAgreementsData?.items || []}
        rowKey="type"
      />

      {!!agreementToChange && (
        <Modal
          fullWidth
          onClose={() => setAgreementToChange(null)}
          title={agreementToChange.accepted ? t('Wycofanie zgody') : t('Akceptacja zgody')}>
          <div className={styles.confirmation}>
            <Trans>Czy {agreementToChange.accepted ? 'wycofać' : 'zaakceptować'} zgodę</Trans>
            <br />
            <strong>&quot;{agreementToChange.content}&quot;</strong>?
            <div className={styles.actions}>
              <Button
                onClick={() => setAgreementToChange(null)}
                color={agreementToChange.accepted ? 'danger' : 'primary'}
                ghost>
                <Trans>Anuluj</Trans>
              </Button>
              <Button
                color={agreementToChange.accepted ? 'danger' : 'primary'}
                onClick={() =>
                  updateAgreement({
                    type: agreementToChange.type,
                    granted: !agreementToChange.accepted
                  })
                }
                loading={isUpdatingAgreement}>
                {agreementToChange.accepted ? t('Wycofaj') : t('Zaakceptuj')}
              </Button>
            </div>
          </div>
        </Modal>
      )}
    </div>
  );
};

export default Agreements;
