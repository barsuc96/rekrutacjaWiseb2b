// strona logowania

import React, { useMemo, useState } from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import { useLocation, useNavigate } from 'react-router-dom';
import qs from 'query-string';

import { reduxActions, useDispatch } from 'store';
import { useNotifications } from 'hooks';
import { usePostAuthLogin } from 'api';
import { LoginFormSchema } from './schema';
import { Input, FormElement, Button, Link, Checkbox } from 'components/controls';
import classnames from 'classnames';

import styles from 'theme/pages/Login/Login.module.scss';

const { REACT_APP_OPEN_PROFILE_CLIENT_ID, REACT_APP_OPEN_PROFILE_CLIENT_SECRET } = process.env;

interface IProps {
  isPopover?: boolean;
}

const Login = ({ isPopover }: IProps) => {
  const location = useLocation();
  const { t, i18n } = useTranslation();
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const { showSuccessMessage, showErrorMessage } = useNotifications();

  const [rememeberMe, setRememberMe] = useState(
    localStorage.getItem('rememberUser') ? true : false
  );

  const returnUrl = useMemo(
    () => String(qs.parseUrl(document.location.href).query.return_url || ''),
    []
  );

  // logowanie do systemu (pobranie tokena autoryzacyjnego)
  const { mutate: login, isLoading: isLogging } = usePostAuthLogin({
    onSuccess: ({ access_token, message, status }) => {
      rememeberMe
        ? localStorage.setItem('rememberUser', values.username)
        : localStorage.removeItem('rememberUser');
      dispatch(reduxActions.signIn({ token: access_token }));

      if (status === 1) {
        navigate('/login');

        return;
      }

      if (access_token) {
        showSuccessMessage(t('Użytkownik zalogowany poprawnie')); // TODO message from api

        if (location.pathname.includes('login')) {
          navigate(returnUrl || `/${i18n.language}`);

          return;
        }

        navigate('/');
      } else {
        showErrorMessage(message || t('Błędne dane użytkownika'));
      }
    }
  });

  // obsługa danych formularza
  const { errors, handleSubmit, values, setFieldValue } = useFormik({
    initialValues: {
      username: localStorage.getItem('rememberUser') || '',
      password: ''
    },
    validationSchema: LoginFormSchema,
    validateOnChange: false,
    onSubmit: (values) => {
      login({
        ...values,
        client_id: REACT_APP_OPEN_PROFILE_CLIENT_ID || '',
        client_secret: REACT_APP_OPEN_PROFILE_CLIENT_SECRET || ''
      });
    }
  });

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-Login')}>
      {isPopover && <h2 className={styles.header}>{t('Logowanie')}</h2>}
      <form className={styles.form} onSubmit={handleSubmit}>
        <FormElement>
          <Input
            placeholder={t('Login')}
            value={values.username}
            onChange={(value) => setFieldValue('username', value)}
            error={errors.username}
          />
        </FormElement>
        <FormElement>
          <Input
            type="password"
            placeholder={t('Hasło')}
            value={values.password}
            onChange={(value) => setFieldValue('password', value)}
            error={errors.password}
          />
        </FormElement>

        {isPopover && (
          <div className={styles.rememberMe}>
            <Checkbox checked={rememeberMe} onClick={() => setRememberMe((prev) => !prev)} />{' '}
            <Trans>Zapamiętaj mnie</Trans>
          </div>
        )}

        <div className={styles.actions}>
          {!isPopover && (
            <Link to="/forgot-password" className={styles.link}>
              <Trans>Odzyskiwanie hasła</Trans>
            </Link>
          )}
          <Button htmlType="submit" loading={isLogging}>
            {t('ZALOGUJ')}
          </Button>
        </div>
        <div className={styles.register}>
          <div>
            <div></div>
            <h2 className={styles.header}>
              <Trans>Rejestracja</Trans>
            </h2>
            <Button onClick={() => navigate('/register')}>{t('ZAŁÓŻ KONTO')}</Button>
          </div>
        </div>
      </form>
    </div>
  );
};

export default Login;
