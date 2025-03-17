// główny layout - górna belka nagłówka

import React from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';
import Tooltip from '@mui/material/Tooltip';

import { useGetUsersTrader } from 'api';
import { useSelector } from 'store';
import { DropDown } from 'components/controls';

import styles from 'theme/components/layouts/MainLayout/components/HeaderTopBar/HeaderTopBar.module.scss';

const Trader = () => {
  const { t } = useTranslation();
  const { profile } = useSelector((state) => state.auth);

  //pobranie opiekuna
  const { data: salesmanData } = useGetUsersTrader();

  return (
    <>
      <span className={styles.user}>
        {t('Zalogowany jako')}{' '}
        {profile?.id !== profile?.logged_user.id ? (
          <Tooltip
            className={styles.overloginTooltip}
            TransitionProps={{
              style: { background: '#fff', borderRadius: 0, border: '1px solid #4917C7' }
            }}
            title={
              <div className={styles.overloginTooltip}>
                <div>
                  <span>
                    <Trans>Użytkownik zalogowany</Trans>: {profile?.logged_user.first_name}{' '}
                    {profile?.logged_user.last_name}
                  </span>
                </div>
                <div>
                  <span>
                    <Trans>Rola</Trans>: {profile?.logged_user.role}
                  </span>
                </div>
              </div>
            }>
            <strong
              className={classnames({
                [styles.overlogin]: true
              })}>
              {profile?.first_name} {profile?.last_name}
            </strong>
          </Tooltip>
        ) : (
          <strong>
            {profile?.first_name} {profile?.last_name}
          </strong>
        )}
      </span>

      {!Array.isArray(salesmanData) && (
        <DropDown
          label={t('Twój opiekun')}
          items={[
            {
              label: (
                <span>
                  {salesmanData?.first_name} {salesmanData?.last_name}
                  <br />
                  <Trans>Telefon</Trans>: <strong>{salesmanData?.phone}</strong>,
                  <br />
                  <Trans>Email</Trans>: <strong>{salesmanData?.email}</strong>
                </span>
              ),
              disabled: true
            }
          ]}
        />
      )}
    </>
  );
};

export default Trader;
