// strana ustawień notyfikacji

import React, { FC } from 'react';

import { usePostUserContractToggle } from 'api';
import { Checkbox } from 'components/controls';

import styles from 'theme/pages/MessageSettings/MessageSettings.module.scss';

// typ danych wejściowych
interface IProps {
  contractId: number;
  name: string;
  enabled: boolean;
  refetchContracts: () => void;
}

const SingleConsent: FC<IProps> = ({ contractId, name, enabled, refetchContracts }) => {
  const { mutate: changeSettings } = usePostUserContractToggle({
    onSuccess: () => {
      refetchContracts();
    }
  });

  return (
    <label className={styles.notification}>
      <b dangerouslySetInnerHTML={{ __html: name }} />

      <Checkbox
        checked={enabled}
        onClick={() => {
          changeSettings({
            items: [{ contractId, contextAgreement: 'DASHBOARD_USER_CONTRACT', isAgree: !enabled }]
          });
        }}
      />
    </label>
  );
};

export default SingleConsent;
