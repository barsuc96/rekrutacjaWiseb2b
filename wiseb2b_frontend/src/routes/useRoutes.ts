// Ruting aplikacji główny hook
import { useEffect, useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { reduxActions, useDispatch, useSelector } from 'store';
import { useGetUserProfile, usePostAuthLogin } from 'api';
import { useLocation } from 'react-router-dom';
import { useSnackbar } from 'notistack';

const useRoutes = () => {
  const { enqueueSnackbar } = useSnackbar();
  const dispatch = useDispatch();
  const location = useLocation();
  const { i18n } = useTranslation();
  const { isAuthenticated, isOpenProfileReady, profile } = useSelector((state) => state.auth);
  const { notification, notificationModal } = useSelector((state) => state.ui);
  const { isLoading: isProfileLoading } = useGetUserProfile({
    enabled: isAuthenticated,
    onSuccess: (data) => dispatch(reduxActions.setProfile(data))
  });
  const { mutate: login } = usePostAuthLogin({
    onSuccess: ({ access_token }) => {
      dispatch(reduxActions.signIn({ token: access_token }));
    },
    onError: () => {
      dispatch(reduxActions.setIsOpenProfileReady(false));
    }
  });

  useEffect(() => {
    isOpenProfileReady &&
      process.env.REACT_APP_OPEN_PROFILE_LOGIN &&
      process.env.REACT_APP_OPEN_PROFILE_PASSWORD &&
      process.env.REACT_APP_OPEN_PROFILE_CLIENT_ID &&
      process.env.REACT_APP_OPEN_PROFILE_CLIENT_SECRET &&
      login({
        username: process.env.REACT_APP_OPEN_PROFILE_LOGIN,
        password: process.env.REACT_APP_OPEN_PROFILE_PASSWORD,
        client_id: process.env.REACT_APP_OPEN_PROFILE_CLIENT_ID,
        client_secret: process.env.REACT_APP_OPEN_PROFILE_CLIENT_SECRET
      });
  }, [isOpenProfileReady]);

  const urlPrefix = `/${i18n.language}`;

  // czyszczenie notyfikacji w store - wyświelane są automatycznie w src/api/axios.ts
  useEffect(() => {
    notification &&
      enqueueSnackbar(notification.message, {
        variant: notification.variant
      });
    dispatch(reduxActions.setNotification(null));
  }, [notification]);

  // url przekierowania w przypadku nieznanego rutingu
  const redirectUrl = useMemo(() => {
    const pathnameArray = location.pathname.split('/').filter((item) => item);
    const langFromURL = pathnameArray[0];
    const supportedLangs = i18n.options.supportedLngs || [];

    // gdy język w urla nie jest obługiwany to dodajemy ustawiony/domyślny język aplikacji
    if (!supportedLangs.includes(langFromURL)) {
      const newLang = i18n.language || (i18n.options.fallbackLng as string);
      i18n.changeLanguage(newLang);
      return `/${newLang}/${pathnameArray.join('/')}${location.search}`;
    }

    // gdy nastąpiła zmiana języka aplikacji
    if (i18n.language !== langFromURL) {
      pathnameArray[0] = i18n.language;
      return `/${pathnameArray.join('/')}${location.search}`;
    }

    // jak caa reszta nie zadziaaa to zwracamy główn;ą stronę języka
    return urlPrefix;
  }, [urlPrefix, location.pathname]);

  return {
    isAuthenticated,
    isProfileLoading,
    isOpenProfileReady,
    urlPrefix,
    redirectUrl,
    profile,
    notificationModal,
    dismissNotificationModal: () => dispatch(reduxActions.setNotificationModal(null))
  };
};

export default useRoutes;
