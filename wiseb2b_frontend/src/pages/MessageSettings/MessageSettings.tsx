// strana ustawień notyfikacji

import React, { useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useGetUserMessageSettings } from 'api';
import { PageTitle } from 'components/controls';
import { SingleMessageSettings } from './components';

import styles from 'theme/pages/MessageSettings/MessageSettings.module.scss';

const DashboardNotifications = () => {
  const { t } = useTranslation();
  const { profile } = useSelector((state) => state.auth);
  const dispatch = useDispatch();

  const { data: userMessageSettingsData, refetch: refetchMessageSettings } =
    useGetUserMessageSettings(profile?.id || 0, {
      page: 1,
      limit: 999
    });

  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Komunikaty') }
      ])
    );
  }, []);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-MessageSettings')}>
      <PageTitle title={t('Komunikaty')} />

      <div className={styles.notifications}>
        {profile?.id &&
          (userMessageSettingsData?.items || []).map((message) => (
            <SingleMessageSettings
              key={message.name}
              userId={profile.id}
              messageId={message.message_settings_id}
              enabled={message.enabled}
              name={message.name}
              refetchMessageSettings={refetchMessageSettings}
            />
          ))}
      </div>
    </div>
  );
};

export default DashboardNotifications;
