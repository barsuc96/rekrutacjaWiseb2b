// główny layout - górna belka nagłówka

import React, { ReactElement } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import { BoxArrowRight } from 'react-bootstrap-icons';
import classnames from 'classnames';
import { useLocation } from 'react-router-dom';
import { Helmet } from 'react-helmet';
import { Gear } from 'react-bootstrap-icons';
import qs from 'query-string';

import { useAppNavigate } from 'hooks';
import { useGetLayoutLanguages, usePostOverLogout, useGetLayoutUrlResolverToLanguage } from 'api';
import { useDispatch, reduxActions, useSelector } from 'store';
import { Container, DropDown, OverLoginUsers, Link } from 'components/controls';
import { Trader } from './components';

import { ReactComponent as FlagPL } from 'assets/flags/pl.svg';
import { ReactComponent as FlagEN } from 'assets/flags/gb.svg';

import styles from 'theme/components/layouts/MainLayout/components/HeaderTopBar/HeaderTopBar.module.scss';
import LoginButton from 'components/containers/LoginButton';

const HeaderTopBar = ({ isAdminPanel }: { isAdminPanel?: boolean }) => {
  const dispatch = useDispatch();
  const { i18n, t } = useTranslation();
  const { profile, overloginUserId } = useSelector((state) => state.auth);
  const { pathname, search } = useLocation();
  const navigate = useAppNavigate();

  // pobranie listy dostępnych języków
  const { data: languagesData } = useGetLayoutLanguages();

  // pobranie url_key po zmianie języka
  const { mutateAsync: refetchUrlResolverToLanguage } = useGetLayoutUrlResolverToLanguage({
    onSuccess: (data) => {
      const params = {
        ...qs.parse(search),
        ...qs.parse(data.parameters)
      };

      navigate(`/${data.url_shortcut}?${qs.stringify(params)}`, { replace: true });
    }
  });

  const getPosition = (string: string, subString: string, index: number) => {
    return string.split(subString, index).join(subString).length;
  };

  const urlPrefix = pathname.slice(0, getPosition(pathname, '/', 2)).replace('/', '');

  const title = languagesData?.items.find((language) => language.id === urlPrefix);

  const { mutateAsync } = usePostOverLogout();

  // wylogowanie uźytkownika z systemu
  const handleLogout = async () => {
    if (overloginUserId) {
      await mutateAsync();

      if (pathname.includes('/cart/')) {
        navigate('/cart/0');
      }

      dispatch(reduxActions.setCurrentCartId(null));
      dispatch(reduxActions.setOverlogin(null));

      return;
    }
    dispatch(reduxActions.signOut());
  };

  // flagi języków
  const flags: Record<string, ReactElement> = {
    en: <FlagEN />,
    pl: <FlagPL />
  };

  const renderAdminPanelButton = () => {
    if (isAdminPanel) {
      return (
        <Link className={styles.link} to="/">
          <BoxArrowRight />
          <Trans>Wróć do sklepu</Trans>
        </Link>
      );
    }

    if (profile?.role === 'ROLE_ADMIN') {
      return (
        <Link className={styles.link} to="/managment_panel">
          <Gear />
          <Trans>Panel administratora</Trans>
        </Link>
      );
    }

    if (profile?.role === 'ROLE_TRADER') {
      return (
        <Link className={styles.link} to="/managment_panel">
          <Gear />
          <Trans>Panel tradera</Trans>
        </Link>
      );
    }

    return null;
  };

  const handleChangeLanguage = async (id: string) => {
    const urlKey = pathname.replace(urlPrefix, '').replaceAll('/', '');

    try {
      await refetchUrlResolverToLanguage({ urlKey, fromLanguage: urlPrefix, toLanguage: id });

      i18n.changeLanguage(id);
    } catch (err) {
      i18n.changeLanguage(id);
    }
  };

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Components-Layouts-MainLayout-Components-HeaderTopBar'
      )}>
      <Helmet>
        <title>{title?.title_website}</title>
        <link rel="canonical" href={window.location.href} />
      </Helmet>
      <Container>
        <div className={styles.topBar}>
          <div>{profile?.role !== 'ROLE_OPEN_PROFILE' && <Trader />}</div>

          <div>
            {renderAdminPanelButton()}
            <DropDown
              label={
                <span className={styles.language}>
                  {flags[i18n.language]}
                  {t('Język')}
                </span>
              }
              items={
                languagesData?.items.map((lang) => ({
                  label: (
                    <span className={styles.language}>
                      {flags[lang.id]}
                      {lang.name}
                    </span>
                  ),
                  onClick: () => {
                    handleChangeLanguage(lang.id);
                  }
                })) || []
              }
              withDropdownIcon={false}
            />

            {profile?.role !== 'ROLE_OPEN_PROFILE' ? (
              <>
                {!overloginUserId && profile?.role !== 'ROLE_USER' && <OverLoginUsers />}

                <button className={styles.authButton} onClick={handleLogout}>
                  {overloginUserId ? t('Wróć na konto') : t('Wyloguj')} <BoxArrowRight />
                </button>
              </>
            ) : (
              <LoginButton />
            )}
          </div>
        </div>
      </Container>
    </div>
  );
};

export default HeaderTopBar;
