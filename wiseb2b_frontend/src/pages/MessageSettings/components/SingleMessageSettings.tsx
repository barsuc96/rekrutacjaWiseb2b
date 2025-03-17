// strana ustawień notyfikacji

import React, { FC } from 'react';

import { usePutUserMessageSettings } from 'api';
import { Checkbox } from 'components/controls';

import styles from 'theme/pages/MessageSettings/MessageSettings.module.scss';

// typ danych wejściowych
interface IProps {
  userId: number;
  messageId: number;
  name: string;
  enabled: boolean;
  refetchMessageSettings: () => void;
}

const SingleMessageSettings: FC<IProps> = ({
  userId,
  messageId,
  name,
  enabled,
  refetchMessageSettings
}) => {
  // wysłanie zapytania do api o zmianę statusu
  const { mutate: changeSettings } = usePutUserMessageSettings(userId, messageId, {
    onSuccess: () => {
      refetchMessageSettings();
    }
  });

  return (
    <label className={styles.notification}>
      {name}

      <Checkbox
        checked={enabled}
        onClick={() => {
          changeSettings({ enabled: !enabled });
        }}
      />
    </label>
  );
};

export default SingleMessageSettings;
