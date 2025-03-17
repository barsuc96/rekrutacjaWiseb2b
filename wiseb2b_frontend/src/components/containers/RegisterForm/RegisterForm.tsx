import React, { useEffect, useMemo } from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import map from 'lodash/map';

import { usePostUserRegister, useGetUsersCountries, useGetUserAgreementRegister } from 'api';
import { useAppNavigate } from 'hooks';
import { IUsersCountryListItem } from 'api/types';
import { Button, Input, FormElement, Checkbox, Select } from 'components/controls';

import styles from 'theme/components/containers/RegisterForm/RegisterForm.module.scss';

const UserForm = () => {
  const { t } = useTranslation();
  const navigate = useAppNavigate();

  // aktualizacja danych profilu
  const { mutate: createProfile, isLoading: isCreatingProfile } = usePostUserRegister({
    onSuccess: () => {
      resetForm();
      navigate('/thankyou_register');
    },
    onError: (errors) => {
      map(errors.fields_info, (field) => setFieldError(field.property_path, field.message));
    }
  });

  // pobieranie listy możliwych krajów
  const { data: articleCountries } = useGetUsersCountries();

  // pobieranie zgód
  const { data: registerConsentData } = useGetUserAgreementRegister();

  // obsługa danych formularza
  const { values, errors, setFieldValue, handleSubmit, setFieldError, resetForm } = useFormik({
    initialValues: {
      first_name: '',
      last_name: '',
      tax_number: '',
      phone: '',
      email: '',
      recaptcha_token: '',

      receiver_address: {
        name: '',
        street: '',
        building: '',
        apartment: '',
        postal_code: '',
        city: '',
        state: '',
        country: '',
        country_code: ''
      },
      agreements: [
        {
          type: 'rules',
          accepted: false
        }
      ]
    },
    onSubmit: (values) => {
      createProfile(values);
    },
    validateOnChange: false,
    enableReinitialize: true
  });

  // ustawienie początkowych zgód
  useEffect(() => {
    if (registerConsentData) {
      const agreements = registerConsentData.items.map((consent) => ({
        type: consent.type,
        accepted: consent.has_active_agree
      }));

      setFieldValue('agreements', agreements);
    }
  }, [registerConsentData]);

  // opcje dropdownu typów pola
  const countryOptions = useMemo(() => {
    const countries = (articleCountries?.items || []).map((country) => ({
      value: country.code,
      label: <span>{country.name}</span>,
      item: country
    }));

    return countries;
  }, [articleCountries]);

  const renderConsent = () => (
    <>
      {values.agreements?.map((agreement, i) => (
        <FormElement key={i}>
          <label className={styles.consent}>
            <Checkbox
              checked={agreement.accepted}
              onClick={() =>
                setFieldValue(`agreements[${i}]`, {
                  type: agreement.type,
                  accepted: !agreement.accepted
                })
              }
            />
            <span
              className={styles.label}
              dangerouslySetInnerHTML={{
                __html: registerConsentData?.items[`${i}`]?.testimony || ''
              }}
            />{' '}
            {registerConsentData?.items[`${i}`]?.user_must_accept && <b>*</b>}
          </label>
        </FormElement>
      ))}
      {registerConsentData?.items.some((contract) => contract.user_must_accept) && (
        <div>
          <b>*</b> - <Trans>Zgoda Wymagana</Trans>
        </div>
      )}
    </>
  );

  return (
    <form className={styles.form} onSubmit={handleSubmit}>
      <div className={styles.accountWrapper}>
        <div className={styles.section}>
          <div className={styles.halfWidth}>
            <div>
              <span className={styles.label}>
                <Trans>Imię</Trans>
              </span>
              <FormElement>
                <Input
                  type="text"
                  value={values.first_name}
                  onChange={(value) => setFieldValue('first_name', value)}
                  error={errors.first_name}
                />
              </FormElement>
            </div>
            <div>
              <span className={styles.label}>
                <Trans>Nazwisko</Trans>
              </span>
              <FormElement>
                <Input
                  type="text"
                  value={values.last_name}
                  onChange={(value) => setFieldValue('last_name', value)}
                  error={errors.last_name}
                />
              </FormElement>
            </div>
          </div>

          <span className={styles.label}>
            <Trans>NIP/VAT EU</Trans>
          </span>
          <FormElement>
            <Input
              type="text"
              value={values.tax_number}
              onChange={(value) => setFieldValue('tax_number', value)}
              error={errors?.tax_number}
            />
          </FormElement>

          <span className={styles.label}>
            <Trans>Nazwa firmy</Trans>
          </span>
          <FormElement>
            <Input
              type="text"
              value={values.receiver_address.name}
              onChange={(value) => setFieldValue('receiver_address.name', value)}
              error={errors?.receiver_address?.name}
            />
          </FormElement>
          <span className={styles.label}>
            <Trans>Kraj</Trans>
          </span>
          <FormElement>
            <div className={styles.selectWrapper}>
              <Select<IUsersCountryListItem>
                options={countryOptions}
                variant="small"
                value={values.receiver_address.country_code}
                onChange={(item) => {
                  if (item?.code) {
                    setFieldValue('receiver_address.country_code', item.code);
                  }
                }}
                error={errors?.receiver_address?.country_code}
              />
            </div>
          </FormElement>

          <div className={styles.halfWidth}>
            <div>
              <span className={styles.label}>
                <Trans>Ulica</Trans>
              </span>
              <FormElement>
                <Input
                  type="text"
                  value={values.receiver_address.street}
                  onChange={(value) => setFieldValue('receiver_address.street', value)}
                  error={errors?.receiver_address?.street}
                />
              </FormElement>
            </div>
            <div>
              <div className={styles.halfWidth}>
                <div>
                  <span className={styles.label}>
                    <Trans>Nr budynku</Trans>
                  </span>
                  <FormElement>
                    <Input
                      type="text"
                      value={values.receiver_address.building}
                      onChange={(value) => setFieldValue('receiver_address.building', value)}
                      error={errors?.receiver_address?.building}
                    />
                  </FormElement>
                </div>
                <div>
                  <span className={styles.label}>
                    <Trans>Nr lokalu</Trans>
                  </span>
                  <FormElement>
                    <Input
                      type="text"
                      value={values.receiver_address.apartment}
                      onChange={(value) => setFieldValue('receiver_address.apartment', value)}
                      error={errors?.receiver_address?.apartment}
                    />
                  </FormElement>
                </div>
              </div>
            </div>
          </div>

          <div className={styles.halfWidth}>
            <div>
              <span className={styles.label}>
                <Trans>Kod pocztowy</Trans>
              </span>
              <FormElement>
                <Input
                  type="text"
                  value={values.receiver_address.postal_code}
                  onChange={(value) => setFieldValue('receiver_address.postal_code', value)}
                  error={errors?.receiver_address?.postal_code}
                />
              </FormElement>
            </div>
            <div>
              <span className={styles.label}>
                <Trans>Miasto</Trans>
              </span>
              <FormElement>
                <Input
                  type="text"
                  value={values.receiver_address.city}
                  onChange={(value) => setFieldValue('receiver_address.city', value)}
                  error={errors?.receiver_address?.city}
                />
              </FormElement>
            </div>
          </div>
        </div>
        <div className={styles.section}>
          <span className={styles.label}>
            <Trans>Telefon</Trans>
          </span>
          <FormElement>
            <Input
              type="text"
              value={values.phone}
              onChange={(value) => setFieldValue('phone', value)}
              error={errors.phone}
            />
          </FormElement>
          <span className={styles.label}>
            <Trans>Email</Trans>
          </span>
          <Input
            placeholder={t('Email')}
            type="text"
            onChange={(value) => setFieldValue('email', value)}
            value={values.email}
            error={errors.email}
          />
          {process.env.REACT_APP_RECAPTHA_SITE_KEY && (
            <div className={styles.captcha}>
              <span className={styles.error}>{errors.recaptcha_token}</span>
            </div>
          )}
          <div className={styles.consentWrapper}>{renderConsent()}</div>
          <Button htmlType="submit" loading={isCreatingProfile}>
            <Trans>Zarejestruj się</Trans>
          </Button>
        </div>
      </div>
    </form>
  );
};

export default UserForm;
