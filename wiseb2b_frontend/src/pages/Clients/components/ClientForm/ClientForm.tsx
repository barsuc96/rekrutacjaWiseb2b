// formularz klienta

import React, { FC, useEffect } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { useFormik } from 'formik';

import {
  useGetClientCountries,
  usePostClient,
  usePutClient,
  useGetClient,
  usePostTokenSecurity,
  useGetTokenSecurity
} from 'api';
import { useNotifications } from 'hooks';
import { transformApiErrorsToFormik } from 'api/helpers';
import { IClientCountryListItem } from 'api/types';
import { Input, Button, Select } from 'components/controls';

import { ClipboardIcon } from 'assets/icons';

import styles from 'theme/pages/Clients/components/ClientForm/ClientForm.module.scss';

// typ danych wejściowych
interface IProps {
  id?: number;
  onCancel: () => void;
  onSuccess: () => void;
}

const ClientForm: FC<IProps> = ({ onCancel, onSuccess, id }) => {
  const { t } = useTranslation();

  const { showSuccessMessage } = useNotifications();

  // pobranie listy krajów
  const { data: clientCountriesData } = useGetClientCountries({ page: 1, limit: 999 });

  // pobranie szczegółów klienta
  const { data: clientData } = useGetClient(id || 0, { enabled: !!id });

  // pobieranie danych clientApi
  const { data: tokenData, refetch: refetchTokenData } = useGetTokenSecurity(id || 0, {
    enabled: !!id,
    keepPreviousData: false
  });

  // generowanie tokena clientApi
  const { mutate: generateToken } = usePostTokenSecurity(id || 0, {
    onSuccess: () => {
      refetchTokenData();
    }
  });

  // tworzenie nowego klienta
  const { mutate: createClient, isLoading: isCreatingClient } = usePostClient({
    onSuccess: () => {
      onSuccess();
      onCancel();
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  // aktualizacja danych klienta
  const { mutate: updateClient, isLoading: isUpdatingClient } = usePutClient(id || 0, {
    onSuccess: () => {
      onSuccess();
      onCancel();
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  useEffect(() => {
    document.addEventListener('keyup', handleKeyPress);

    return () => window.removeEventListener('keyup', handleKeyPress);
  }, []);

  const handleKeyPress = (e: KeyboardEvent) => {
    if (e.key === 'Escape') {
      onCancel();
    }

    if (e.key === 'Enter') {
      handleSubmit();
    }
  };

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit, setErrors } = useFormik({
    initialValues: {
      name: clientData?.name || '',
      street: clientData?.address.street || '',
      postal_code: clientData?.address.postal_code || '',
      city: clientData?.address.city || '',
      country: clientData?.address.country_code || '',
      nip: '',
      apartment: clientData?.address.apartment || '',
      building: clientData?.address.building || '',
      contact_person_first_name: clientData?.first_name || '',
      contact_person_last_name: clientData?.last_name || '',
      email: clientData?.email || '',
      phone: clientData?.phone || ''
    },
    onSubmit: (values) => {
      id ? updateClient(values) : createClient(values);
    },
    validateOnChange: false,
    enableReinitialize: true
  });

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Clients-Components-ClientForm'
      )}>
      <div className={styles.half}>
        <div className={styles.formElement}>
          <label>
            <Trans>Nazwa firmy</Trans>
          </label>
          <Input
            value={values.name}
            onChange={(value) => setFieldValue('name', value)}
            error={errors.name}
          />
        </div>

        <div className={styles.formElement}>
          <label>
            <Trans>Ulica</Trans>
          </label>
          <Input
            value={values.street}
            onChange={(value) => setFieldValue('street', value)}
            error={errors.street}
          />
        </div>

        <div className={styles.formElement}>
          <label>
            <Trans>Numer domu</Trans>
          </label>
          <Input
            value={values.building}
            onChange={(value) => setFieldValue('building', value)}
            error={errors.building}
          />
        </div>

        <div className={styles.halfElement}>
          <div className={classnames(styles.formElement, styles.halfWidth)}>
            <label>
              <Trans>Kod pocztowy</Trans>
            </label>
            <Input
              value={values.postal_code}
              onChange={(value) => setFieldValue('postal_code', value)}
              error={errors.postal_code}
            />
          </div>

          <div className={classnames(styles.formElement, styles.halfWidth)}>
            <label>
              <Trans>Miasto</Trans>
            </label>
            <Input
              value={values.city}
              onChange={(value) => setFieldValue('city', value)}
              error={errors.city}
            />
          </div>
        </div>

        <div className={styles.halfElement}>
          <div className={styles.formElement}>
            <label>
              <Trans>Kraj</Trans>
            </label>
            <Select<IClientCountryListItem>
              value={values.country}
              options={
                clientCountriesData?.items.map((country) => ({
                  value: country.code,
                  label: country.name,
                  item: country
                })) || []
              }
              onChange={(item) => setFieldValue('country', item?.code)}
              error={errors.country}
            />
          </div>
          <div className={styles.formElement}>
            <label>
              <Trans>NIP</Trans>
            </label>
            <Input
              value={values.nip}
              onChange={(value) => setFieldValue('nip', value)}
              error={errors.nip}
            />
          </div>
        </div>
      </div>

      <div className={styles.half}>
        <div className={styles.formElement}>
          <label>
            <Trans>Imię</Trans>
          </label>
          <Input
            value={values.contact_person_first_name}
            onChange={(value) => setFieldValue('contact_person_first_name', value)}
            error={errors.contact_person_first_name}
          />
        </div>
        <div className={styles.formElement}>
          <label>
            <Trans>Nazwisko</Trans>
          </label>
          <Input
            value={values.contact_person_last_name}
            onChange={(value) => setFieldValue('contact_person_last_name', value)}
            error={errors.contact_person_last_name}
          />
        </div>

        <div className={styles.formElement}>
          <label>
            <Trans>Numer lokalu</Trans>
          </label>
          <Input
            value={values.apartment}
            onChange={(value) => setFieldValue('apartment', value)}
            error={errors.apartment}
          />
        </div>

        <div className={styles.formElement}>
          <label>
            <Trans>Email</Trans>
          </label>
          <Input
            value={values.email}
            onChange={(value) => setFieldValue('email', value)}
            error={errors.email}
          />
        </div>

        <div className={styles.formElement}>
          <label>
            <Trans>Telefon</Trans>
          </label>
          <Input
            value={values.phone || ''}
            onChange={(value) => setFieldValue('phone', value)}
            error={errors.phone}
          />
        </div>
      </div>
      {id && (
        <>
          <div className={styles.clientApiTitle}>ClientAPI</div>
          <div className={styles.accountWrapper}>
            <div className={styles.tokenWrapper}>
              <div className={styles.formElement}>
                <label>Client Id</label>
                <Input value={tokenData?.client_id} disabled />
                <ClipboardIcon
                  onClick={() => {
                    navigator.clipboard.writeText(tokenData?.client_id || '');
                    showSuccessMessage(t('Skopiowano token do schowka'));
                  }}
                />
              </div>
              <div className={styles.formElement}>
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

      <div className={styles.actions}>
        <Button onClick={onCancel} color="secondary" ghost>
          <Trans>Anuluj</Trans>
        </Button>

        <Button
          loading={isCreatingClient || isUpdatingClient}
          onClick={() => handleSubmit()}
          color="secondary">
          <Trans>Zapisz</Trans>
        </Button>
      </div>
    </div>
  );
};

export default ClientForm;
