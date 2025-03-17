// formularz zmiany hasła

import React, { FC, useMemo } from 'react';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { usePutUserProfile, useGetCountryCodes } from 'api';
import { IUserProfile, ICountryCodeItem } from 'api/types';
import { transformApiErrorsToFormik } from 'api/helpers';
import { Input, Button, FormElement, Select } from 'components/controls';

import styles from 'theme/components/containers/ChangePasswordForm/ChangePasswordForm.module.scss';

interface IProps {
  profile: IUserProfile;
  onCancel?: () => void;
  refetchProfile?: () => void;
}

const ChangeProfileForm: FC<IProps> = ({ profile, onCancel, refetchProfile }) => {
  const { t } = useTranslation();

  // akcja zmiany danych profilowych
  const { mutate: changeProfile, isLoading: isChanging } = usePutUserProfile(profile.id, {
    onSuccess: () => {
      onCancel?.();
      refetchProfile?.();
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  // Pobranie listy krajów
  const { data: countryData, isLoading: countryLoading } = useGetCountryCodes();

  const { values, errors, setFieldValue, handleSubmit, setErrors } = useFormik({
    initialValues: {
      first_name: profile.first_name || '',
      last_name: profile.last_name || '',
      email: profile.email || '',
      customer: {
        name: profile.customer.name || '',
        address: {
          street: profile.customer.address.street || '',
          building: profile.customer.address.house_number || '',
          postal_code: profile.customer.address.postal_code || '',
          city: profile.customer.address.city || '',
          country: profile.customer.address.country || ''
        },
        nip: profile.customer.nip || '',
        email: profile.customer.email || '',
        phone: profile.customer.phone || ''
      }
    },
    validationSchema: Yup.object().shape({
      first_name: Yup.string()
        .matches(/^[A-Za-z\-\s]*$/, t('W tym polu dozwolone są tylko litery alfabetu'))
        .required(t('Pole wymagane')),
      last_name: Yup.string()
        .matches(/^[A-Za-z\-\s]*$/, t('W tym polu dozwolone są tylko litery alfabetu'))
        .required(t('Pole wymagane')),
      email: Yup.string().email(t('Niepoprawny adres email')).required(t('Pole wymagane')),
      customer: Yup.object().shape({
        name: Yup.string().required(t('Pole wymagane')),
        address: Yup.object().shape({
          street: Yup.string().required(t('Pole wymagane')),
          building: Yup.string().required(t('Pole wymagane')),
          postal_code: Yup.string()
            .matches(/^([0-9]{2}-[0-9]{3})*$/, t('Niepoprawny kod pocztowy'))
            .required(t('Pole wymagane')),
          city: Yup.string().required(t('Pole wymagane')),
          country: Yup.string().required(t('Pole wymagane'))
        }),
        nip: Yup.string()
          .matches(/^[0-9]{10}/, t('Błędny NIP'))
          .length(10, t('Błędny NIP'))
          .required(t('Pole wymagane')),
        email: Yup.string().email(t('Niepoprawny adres email')).required(t('Pole wymagane')),
        phone: Yup.string()
          .matches(
            /^\+?[1-9][0-9]{7,14}$/,
            `${t(
              'Niepoprawny numer telefonu. Możliwe formaty:'
            )} +48555555555, 48555555555, 555555555`
          )
          .required(t('Pole wymagane'))
      })
    }),
    onSubmit: (values) => {
      changeProfile(values);
    },
    validateOnChange: false
  });

  const countryOptions = useMemo(
    () =>
      countryData?.items.map((country) => ({
        value: country.code,
        label: country.language,
        item: country
      })) || [],
    [countryData]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ChangePasswordForm'
      )}>
      <h2>
        <Trans>Dane kontaktowe</Trans>
      </h2>
      <FormElement>
        <label>
          <Trans>Imię</Trans>
        </label>
        <Input
          value={values.first_name}
          onChange={(value) => setFieldValue('first_name', value)}
          error={errors.first_name}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>Nazwisko</Trans>
        </label>
        <Input
          value={values.last_name}
          onChange={(value) => setFieldValue('last_name', value)}
          error={errors.last_name}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>Email</Trans>
        </label>
        <Input
          value={values.email}
          onChange={(value) => setFieldValue('email', value)}
          error={errors.email}
        />
      </FormElement>

      <h2>
        <Trans>Dane firmy</Trans>
      </h2>
      <FormElement>
        <label>
          <Trans>Nazwa firmy</Trans>
        </label>
        <Input
          value={values.customer.name}
          onChange={(value) => setFieldValue('customer.name', value)}
          error={errors.customer?.name}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>NIP</Trans>
        </label>
        <Input
          value={values.customer.nip}
          onChange={(value) => setFieldValue('customer.nip', value)}
          error={errors.customer?.nip}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>Ulica</Trans>
        </label>
        <Input
          value={values.customer.address.street}
          onChange={(value) => setFieldValue('customer.address.street', value)}
          error={errors.customer?.address?.street}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Numer domu</Trans>
        </label>
        <Input
          value={values.customer.address.building}
          onChange={(value) => setFieldValue('customer.address.building', value)}
          error={errors.customer?.address?.building}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Kod pocztowy</Trans>
        </label>
        <Input
          value={values.customer.address.postal_code}
          onChange={(value) => setFieldValue('customer.address.postal_code', value)}
          error={errors.customer?.address?.postal_code}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Miasto</Trans>
        </label>
        <Input
          value={values.customer.address.city}
          onChange={(value) => setFieldValue('customer.address.city', value)}
          error={errors.customer?.address?.city}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Kraj</Trans>
        </label>
        <Select<ICountryCodeItem>
          options={countryOptions}
          variant="bordered"
          disabled={countryLoading}
          value={values.customer.address.country}
          onChange={(item) => {
            setFieldValue('customer.address.country', item?.code);
          }}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Email</Trans>
        </label>
        <Input
          value={values.customer.email}
          onChange={(value) => setFieldValue('customer.email', value)}
          error={errors.customer?.email}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Telefon</Trans>
        </label>
        <Input
          value={values.customer.phone}
          onChange={(value) => setFieldValue('customer.phone', value)}
          error={errors.customer?.phone}
        />
      </FormElement>

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

export default ChangeProfileForm;
