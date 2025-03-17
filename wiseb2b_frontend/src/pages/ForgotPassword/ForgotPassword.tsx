// strona rozpoczęcia procesu resetu hasła

import React from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import * as Yup from 'yup';
import classnames from 'classnames';

import { usePostAuthPasswordForgot } from 'api';
import { useAppNavigate } from 'hooks';
import { Input, FormElement, Button } from 'components/controls';
import { transformApiErrorsToFormik } from '../../api/helpers';

import styles from 'theme/pages/ForgotPassword/ForgotPassword.module.scss';

const ForgotPassword = () => {
  const { t } = useTranslation();
  const navigate = useAppNavigate();

  // rozpoczęcie procesu odzyskiwania hasła
  const { mutate: forgotPasswordRequest, isLoading } = usePostAuthPasswordForgot({
    onSuccess: () => {
      resetForm();
      navigate('/login');
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  // obsługa danych formularza
  const { errors, handleSubmit, values, setFieldValue, resetForm, setErrors } = useFormik({
    initialValues: {
      email: ''
    },
    validationSchema: Yup.object().shape({
      email: Yup.string().required(t('Pole wymagane'))
    }),
    validateOnChange: false,
    onSubmit: (values) => {
      forgotPasswordRequest(values);
    }
  });

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-ForgotPassword')}>
      <h2>
        <Trans>Odzyskiwanie hasła</Trans>
      </h2>
      <form className={styles.form} onSubmit={handleSubmit}>
        <FormElement>
          <Input
            placeholder={t('Email')}
            value={values.email}
            onChange={(value) => setFieldValue('email', value)}
            error={errors.email}
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

export default ForgotPassword;
