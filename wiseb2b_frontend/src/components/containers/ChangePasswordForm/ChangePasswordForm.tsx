// formularz zmiany hasła

import React, { FC } from 'react';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { usePostAuthPasswordChange, useGetUserProfile } from 'api';
import { transformApiErrorsToFormik } from 'api/helpers';
import { Input, Button } from 'components/controls';

import styles from 'theme/components/containers/ChangePasswordForm/ChangePasswordForm.module.scss';

interface IProps {
  onCancel?: () => void;
}

const ChangePasswordForm: FC<IProps> = ({ onCancel }) => {
  const { t } = useTranslation();

  // pobranie danych profilowych
  const { refetch: refetchProfile } = useGetUserProfile({ enabled: false });

  // akcja zmiany hasła
  const { mutate: changePassword, isLoading: isChanging } = usePostAuthPasswordChange({
    onSuccess: () => {
      refetchProfile();
      onCancel?.();
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  const { values, errors, setFieldValue, handleSubmit, setErrors } = useFormik({
    initialValues: {
      old_password: '',
      new_password: '',
      confirm_password: ''
    },
    validationSchema: Yup.object().shape({
      old_password: Yup.string().required(t('Pole wymagane')),
      new_password: Yup.string().required(t('Pole wymagane')),
      confirm_password: Yup.string()
        .required(t('Pole wymagane'))
        .oneOf([Yup.ref('new_password')], t('Proszę potwierdzić nowe hasło'))
    }),
    onSubmit: (values) => {
      changePassword(values);
    },
    validateOnChange: false
  });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ChangePasswordForm'
      )}>
      <div className={styles.formElement}>
        <label>
          <Trans>Stare hasło</Trans>
        </label>
        <Input
          type="password"
          value={values.old_password}
          onChange={(value) => setFieldValue('old_password', value)}
          error={errors.old_password}
        />
      </div>

      <div className={styles.formElement}>
        <label>
          <Trans>Nowe hasło</Trans>
        </label>
        <Input
          type="password"
          value={values.new_password}
          onChange={(value) => setFieldValue('new_password', value)}
          error={errors.new_password}
        />
      </div>

      <div className={styles.formElement}>
        <label>
          <Trans>Powtórz hasło</Trans>
        </label>
        <Input
          type="password"
          value={values.confirm_password}
          onChange={(value) => setFieldValue('confirm_password', value)}
          error={errors.confirm_password}
        />
      </div>

      <div className={styles.actions}>
        {!!onCancel && (
          <Button onClick={onCancel} color="secondary" ghost>
            <Trans>Anuluj</Trans>
          </Button>
        )}
        <Button color="secondary" loading={isChanging} onClick={() => handleSubmit()}>
          <Trans>Zmień</Trans>
        </Button>
      </div>
    </div>
  );
};

export default ChangePasswordForm;
