// strona Moje konto

import React, { FC, PropsWithChildren, useEffect, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { Pencil } from 'react-bootstrap-icons';
import { Grid } from '@mui/material';
import classnames from 'classnames';

import { useGetUserProfile, useGetTokenSecurity, usePostTokenSecurity } from 'api';
import { reduxActions, useDispatch, useSelector } from 'store';
import { useNotifications } from 'hooks';
import { PageTitle, Button, Modal, Input } from 'components/controls';
import { ChangePasswordForm, ChangeProfileForm } from 'components/containers';

import { ClipboardIcon } from 'assets/icons';

import styles from 'theme/pages/MyAccount/MyAccount.module.scss';

interface ITitleProps extends PropsWithChildren {
  secondary?: boolean;
}

const DashboardMyAccount = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { profile } = useSelector((state) => state.auth);

  const { showSuccessMessage } = useNotifications();

  // pobranie danych profilowych
  const { refetch: refetchProfile } = useGetUserProfile({ enabled: false });

  // pobieranie danych clientApi
  const { data: tokenData, refetch: refetchTokenData } = useGetTokenSecurity(profile?.id || 0, {
    keepPreviousData: false
  });

  // generowanie tokena clientApi
  const { mutate: generateToken } = usePostTokenSecurity(profile?.id || 0, {
    onSuccess: () => {
      refetchTokenData();
    }
  });

  // czy modal ze zmianą hasła jest widoczny?
  const [isChangePasswordModal, setIsChangePasswordModal] = useState(false);

  // czy modal ze zmianą hasła jest widoczny?
  const [isChangeProfileModal, setIsChangeProfileModal] = useState(false);

  // ustawienie breadcrumbs'ów na starcie strony
  useEffect(() => {
    dispatch(
      reduxActions.setBreadcrumbs([
        { name: t('Dashboard'), path: '/dashboard' },
        { name: t('Moje konto') }
      ])
    );
  }, []);

  const Title: FC<ITitleProps> = ({ children, secondary }) => (
    <div className={classnames(styles.title, { [styles.secondary]: secondary })}>{children}</div>
  );

  const Info: FC<PropsWithChildren> = ({ children }) => (
    <div className={styles.info}>{children}</div>
  );

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-MyAccount')}>
      <PageTitle title={t('Moje konto')} />
      <div className={styles.accountWrapper}>
        <div className={styles.login}>
          {profile?.email}
          <Pencil onClick={() => setIsChangeProfileModal(true)} />
        </div>

        <Grid container rowGap="32px">
          <Grid item sm={6} xs={12}>
            <Title>
              <Trans>Dane firmy</Trans>
            </Title>
            <Info>{profile?.customer.name}</Info>
            <Info>{profile?.customer.address?.street}</Info>
            <Info>
              {profile?.customer.address?.postal_code}, {profile?.customer.address?.city}
            </Info>
            <Info>
              <Trans>NIP</Trans> {profile?.customer.nip}
            </Info>
            <Info>{profile?.customer.email}</Info>
          </Grid>

          <Grid item sm={6} xs={12}>
            <Title>
              <Trans>Dane kontaktowe</Trans>
            </Title>
            <Info>
              {profile?.first_name} {profile?.last_name}
            </Info>
            <Info>{profile?.email}</Info>

            <Title secondary>
              <Trans>Dane logowania</Trans>
            </Title>
            <Button onClick={() => setIsChangePasswordModal(true)}>
              <Trans>Zmień hasło</Trans>
            </Button>
          </Grid>
        </Grid>
      </div>

      {tokenData?.client_id && tokenData?.client_secret && profile?.id && (
        <>
          <PageTitle title="ClientAPI" />
          <div className={styles.accountWrapper}>
            <div className={styles.tokenWrapper}>
              <div>
                <label>Client Id</label>
                <Input value={tokenData?.client_id} disabled />
                <ClipboardIcon
                  onClick={() => {
                    navigator.clipboard.writeText(tokenData?.client_id || '');
                    showSuccessMessage(t('Skopiowano token do schowka'));
                  }}
                />
              </div>
              <div>
                <label>Client Secret</label>
                <Input value={tokenData?.client_secret} disabled />
                <ClipboardIcon
                  onClick={() => {
                    navigator.clipboard.writeText(tokenData?.client_secret || '');
                    showSuccessMessage(t('Skopiowano token do schowka'));
                  }}
                />
              </div>

              <Button onClick={() => generateToken()}>
                <Trans>Generuj nowy klucz dostępu</Trans>
              </Button>
            </div>
          </div>
        </>
      )}

      {isChangePasswordModal && (
        <Modal onClose={() => setIsChangePasswordModal(false)} title={t('Zmień hasło')}>
          <ChangePasswordForm onCancel={() => setIsChangePasswordModal(false)} />
        </Modal>
      )}

      {isChangeProfileModal && (
        <Modal onClose={() => setIsChangeProfileModal(false)} title={t('Edytuj profil')}>
          {profile && (
            <ChangeProfileForm
              profile={profile}
              onCancel={() => setIsChangeProfileModal(false)}
              refetchProfile={refetchProfile}
            />
          )}
        </Modal>
      )}
    </div>
  );
};

export default DashboardMyAccount;
