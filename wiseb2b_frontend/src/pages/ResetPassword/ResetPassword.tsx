// strona rozpoczęcia procesu resetu hasła

import React from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import * as Yup from 'yup';
import { useParams } from 'react-router-dom';
import classnames from 'classnames';


import { usePostAuthPasswordReset } from 'api';
import { useAppNavigate } from 'hooks';
import { Input, FormElement, Button } from 'components/controls';

import styles from 'theme/pages/ResetPassword/ResetPassword.module.scss';

const ResetPassword = () => {
  const { t } = useTranslation();
  const { token } = useParams();
  const navigate = useAppNavigate();

  // reset hasła
  const { mutate: resetPassword, isLoading } = usePostAuthPasswordReset({
    onSuccess: () => {
      resetForm();
      navigate('/login');
    }
  });

  // obsługa danych formularza
  const { values, errors, setFieldValue, handleSubmit, resetForm } = useFormik({
    initialValues: {
      password: '',
      confirm_password: ''
    },
    validationSchema: Yup.object().shape({
      password: Yup.string().required(t('Pole wymagane')),
      confirm_password: Yup.string()
        .required(t('Pole wymagane'))
        .oneOf([Yup.ref('password')], t('Proszę potwierdzić nowe hasło'))
    }),
    onSubmit: (values) => {
      token &&
        resetPassword({
          token,
          password: values.password
        });
    },
    validateOnChange: false
  });

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-ResetPassword')}>
      <h2>
        <Trans>Reset hasła</Trans>
      </h2>
      <form className={styles.form} onSubmit={handleSubmit}>
        <FormElement>
          <Input
            placeholder={t('Nowe hasło')}
            type="password"
            value={values.password}
            onChange={(value) => setFieldValue('password', value)}
            error={errors.password}
          />
        </FormElement>

        <FormElement>
          <Input
            placeholder={t('Powtórz hasło')}
            type="password"
            value={values.confirm_password}
            onChange={(value) => setFieldValue('confirm_password', value)}
            error={errors.confirm_password}
          />
        </FormElement>

        <div className={styles.actions}>
          <Button htmlType="submit" loading={isLoading}>
            {t('Wyślij')}
          </Button>
        </div>
      </form>
    </div>
  );
};

export default ResetPassword;
