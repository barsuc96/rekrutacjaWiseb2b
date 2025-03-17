// strana ustawień notyfikacji

import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useGetUserContract } from 'api';
import { PageTitle } from 'components/controls';
import { SingleConsent } from './components';

import styles from 'theme/pages/MessageSettings/MessageSettings.module.scss';

const Consent = () => {
  const { t } = useTranslation();
  const { profile } = useSelector((state) => state.auth);
  const dispatch = useDispatch();

  const { data: userContractData, refetch: refetchContracts } = useGetUserContract({
    page: 1,
    limit: 999
  });

  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Zgody') }
      ])
    );
  }, []);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-MessageSettings')}>
      <PageTitle title={t('Zgody')} />

      <div className={styles.notifications}>
        {profile?.id &&
          (userContractData?.items || []).map((contract) => (
            <SingleConsent
              key={contract.id}
              contractId={contract.id}
              enabled={contract.has_active_agree}
              name={contract.testimony}
              refetchContracts={refetchContracts}
            />
          ))}
      </div>
    </div>
  );
};

export default Consent;
