// formularz do aktualizacji danych odbiorcy

import React, { FC, useMemo } from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useGetClientCountries } from 'api';
import { ICartMainDataUpdateRequest, IClientCountryListItem } from 'api/types';
import { Input, Button, Select } from 'components/controls';

import styles from 'theme/pages/Checkout/components/CustomerForm/CustomerForm.module.scss';

// typ danych wejściowych
interface IProps {
  customer: NonNullable<ICartMainDataUpdateRequest['client']>;
  updateCartMainData: (data: Partial<ICartMainDataUpdateRequest>) => void;
  isCartMainDataUpdating?: boolean;
  onCancel: () => void;
}

const CustomerForm: FC<IProps> = ({
  onCancel,
  customer,
  updateCartMainData,
  isCartMainDataUpdating
}) => {
  const { t } = useTranslation();

  // obsługa danych formularza
  const { values, errors, setFieldValue, handleSubmit } = useFormik<
    NonNullable<ICartMainDataUpdateRequest['client']>
  >({
    initialValues: customer,
    onSubmit: (values) => {
      updateCartMainData({ client: values });
    },
    validateOnChange: false
  });

  // pobranie listy krajów
  const { data: clientCountriesData } = useGetClientCountries({ page: 1, limit: 999 });

  // opcje dropdownu typów pola odbiorce
  const receiverCountryOptions = useMemo(() => {
    const sections = (clientCountriesData?.items || []).map((country) => ({
      value: country.code,
      label: <span>{country.name}</span>,
      item: country
    }));

    return sections;
  }, [clientCountriesData]);

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-CustomerForm'
      )}>
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
          value={values.address.street}
          onChange={(value) => setFieldValue('address.street', value)}
          error={errors.address?.street}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Numer budynku</Trans>
        </label>
        <Input
          value={values.address.building}
          onChange={(value) => setFieldValue('address.building', value)}
          error={errors.address?.building}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Numer lokalu</Trans>
        </label>
        <Input
          value={values.address.apartment}
          onChange={(value) => setFieldValue('address.apartment', value)}
          error={errors.address?.apartment}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Kod pocztowy</Trans>
        </label>
        <Input
          value={values.address.postal_code}
          onChange={(value) => setFieldValue('address.postal_code', value)}
          error={errors.address?.postal_code}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Miasto</Trans>
        </label>
        <Input
          value={values.address.city}
          onChange={(value) => setFieldValue('address.city', value)}
          error={errors.address?.city}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>NIP</Trans>
        </label>
        <Input
          value={values.nip}
          onChange={(value) => setFieldValue('nip', value)}
          error={errors.nip}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Email</Trans>
        </label>
        <Input
          value={values.email}
          onChange={(value) => setFieldValue('email', value)}
          error={errors.email}
        />
      </div>

      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Telefon</Trans>
        </label>
        <Input
          value={values.phone || ''}
          onChange={(value) => setFieldValue('phone', value)}
          error={errors.phone}
        />
      </div>
      <div className={classnames(styles.formElement, styles.halfWidth)}>
        <label>
          <Trans>Państwo</Trans>
        </label>
        <Select<IClientCountryListItem>
          options={receiverCountryOptions}
          placeholder={t('Wybierz...')}
          variant="small"
          value={values.address.country_code}
          onChange={(item) => {
            if (item?.code) {
              setFieldValue('address.country_code', item?.code);
            }
          }}
        />
      </div>

      <div className={styles.actions}>
        <Button onClick={onCancel} color="secondary" ghost>
          {t('Anuluj')}
        </Button>
        <Button color="secondary" loading={isCartMainDataUpdating} onClick={() => handleSubmit()}>
          {t('Zapisz')}
        </Button>
      </div>
    </div>
  );
};

export default CustomerForm;
