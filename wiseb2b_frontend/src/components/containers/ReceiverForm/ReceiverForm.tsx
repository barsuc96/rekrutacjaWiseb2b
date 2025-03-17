// formularz dodawania odbiorcy

import React, { FC, useMemo } from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import map from 'lodash/map';
import classnames from 'classnames';

import { usePostReceiver, useGetReceiversCountries } from 'api';
import { IReceiverRequest, IReceiversCountryListItem } from 'api/types';
import { Input, Button, FormElement, Select } from 'components/controls';

import styles from 'theme/components/containers/ReceiverForm/ReceiverForm.module.scss';

// typ danych wejściowych
interface IProps {
  onCancel: () => void;
  onSuccess: (receiverId: number) => void;
}

const ReceiverForm: FC<IProps> = ({ onCancel, onSuccess }) => {
  const { t } = useTranslation();

  // pobieranie krajów odbiorców
  const { data: receiversCountriesData } = useGetReceiversCountries();

  // opcje dropdownu typów pola odbiorce
  const receiverCountryOptions = useMemo(() => {
    const sections = (receiversCountriesData?.items || []).map((country) => ({
      value: country.code,
      label: <span>{country.name}</span>,
      item: country
    }));

    return sections;
  }, [receiversCountriesData]);

  // dodawanie użytkowni
  const { mutate: addReceiver, isLoading: isReceiverCreating } = usePostReceiver({
    onSuccess: (data) => {
      onSuccess(data.data.id);
      onCancel();
    },
    onError: (errors) => {
      map(errors.fields_info, (field) => setFieldError(field.property_path, field.message));
    }
  });

  // obsługa danych formularza
  const { values, errors, setFieldValue, handleSubmit, setFieldError } =
    useFormik<IReceiverRequest>({
      initialValues: {
        email: '',
        name: '',
        first_name: '',
        last_name: '',
        phone: '',
        address: {
          street: '',
          city: '',
          postal_code: '',
          building: '',
          apartment: '',
          country: ''
        }
      },
      onSubmit: (values) => {
        addReceiver(values);
      },
      validateOnChange: false
    });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-ReceiverForm'
      )}>
      <FormElement>
        <label>
          <Trans>Nazwa firmy</Trans>
        </label>
        <Input
          value={values.name}
          onChange={(value) => setFieldValue('name', value)}
          error={errors.name}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Imię</Trans>
        </label>
        <Input
          value={values.first_name}
          onChange={(value) => setFieldValue('first_name', value)}
          error={errors.first_name}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Nazwisko</Trans>
        </label>
        <Input
          value={values.last_name}
          onChange={(value) => setFieldValue('last_name', value)}
          error={errors.last_name}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Email</Trans>
        </label>
        <Input
          value={values.email}
          onChange={(value) => setFieldValue('email', value)}
          error={errors.email}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Telefon</Trans>
        </label>
        <Input
          value={values.phone}
          onChange={(value) => setFieldValue('phone', value)}
          error={errors.phone}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Ulica</Trans>
        </label>
        <Input
          value={values.address.street}
          onChange={(value) => setFieldValue('address.street', value)}
          error={errors.address?.street}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Numer budynku</Trans>
        </label>
        <Input
          value={values.address.building}
          onChange={(value) => setFieldValue('address.building', value)}
          error={errors.address?.building}
        />
      </FormElement>
      <FormElement halfWidth>
        <label>
          <Trans>Numer lokalu</Trans>
        </label>
        <Input
          value={values.address.apartment}
          onChange={(value) => setFieldValue('address.apartment', value)}
          error={errors.address?.apartment}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Kod pocztowy</Trans>
        </label>
        <Input
          value={values.address.postal_code}
          onChange={(value) => setFieldValue('address.postal_code', value)}
          error={errors.address?.postal_code}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Miasto</Trans>
        </label>
        <Input
          value={values.address.city}
          onChange={(value) => setFieldValue('address.city', value)}
          error={errors.address?.city}
        />
      </FormElement>
      <FormElement halfWidth>
        <label>
          <Trans>Państwo</Trans>
        </label>
        <Select<IReceiversCountryListItem>
          options={receiverCountryOptions}
          placeholder={t('Wybierz...')}
          variant="small"
          value={values.address.country}
          onChange={(item) => {
            if (item?.code) {
              setFieldValue('address.country', item?.code);
            }
          }}
        />
        <span className={styles.error}>{errors.address?.country}</span>
      </FormElement>

      <div className={styles.actions}>
        <Button onClick={onCancel} color="secondary" ghost>
          {t('Anuluj')}
        </Button>
        <Button color="secondary" loading={isReceiverCreating} onClick={() => handleSubmit()}>
          {t('Zapisz')}
        </Button>
      </div>
    </div>
  );
};

export default ReceiverForm;
