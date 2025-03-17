// formularz dodawania użytkownika

import React, { FC } from 'react';
import { useFormik } from 'formik';
import { Trans } from 'react-i18next';
import classnames from 'classnames';
import map from 'lodash/map';

import { usePostUsers, useGetUsersTraders } from 'api';
import { IPostUsersRequest, ITraderListItem } from 'api/types';
import { Button, FormElement, Input, Select } from 'components/controls';

import styles from 'theme/pages/Users/components/UserForm/UserForm.module.scss';

// typ danych wejściowych
interface IProps {
  onCancel: () => void;
  onSuccess: () => void;
}

const UserForm: FC<IProps> = ({ onCancel, onSuccess }) => {
  // stworzenie użytkownika
  const { mutate: createUser, isLoading: isSaving } = usePostUsers({
    onSuccess: () => {
      onSuccess();
      onCancel();
    },
    onError: (errors) => {
      map(errors.fields_info, (field) => setFieldError(field.property_path, field.message));
    }
  });

  //pobranie listy handlowców
  const { data: salesmansData } = useGetUsersTraders({ page: 1, limit: 999 });

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit, setFieldError } = useFormik<
    Partial<IPostUsersRequest>
  >({
    initialValues: {},
    onSubmit: (values) => {
      createUser({
        first_name: values.first_name || '',
        last_name: values.last_name || '',
        email: values.email || '',
        trader_id: values.trader_id
      });
    },
    validateOnChange: false
  });

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Users-components-UserForm')}>
      <FormElement halfWidth>
        <label>
          <Trans>Imię</Trans>
        </label>
        <Input
          value={values.first_name || ''}
          onChange={(value) => setFieldValue('first_name', value)}
          error={errors.first_name}
        />
      </FormElement>

      <FormElement halfWidth>
        <label>
          <Trans>Nazwisko</Trans>
        </label>
        <Input
          value={values.last_name || ''}
          onChange={(value) => setFieldValue('last_name', value)}
          error={errors.last_name}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>Email</Trans>
        </label>
        <Input
          value={values.email || ''}
          onChange={(value) => setFieldValue('email', value)}
          error={errors.email}
        />
      </FormElement>

      <FormElement>
        <label>
          <Trans>Handlowiec</Trans>
        </label>
        <Select<ITraderListItem>
          onChange={(item) => setFieldValue('trader_id', item?.id)}
          value={values.trader_id}
          options={
            salesmansData?.items.map((salesman) => ({
              value: salesman.id,
              label: `${salesman.first_name} ${salesman.last_name}`,
              item: salesman
            })) || []
          }
          error={errors.trader_id}
        />
      </FormElement>

      <div className={styles.actions}>
        <Button onClick={onCancel} ghost>
          <Trans>Anuluj</Trans>
        </Button>
        <Button loading={isSaving} onClick={() => handleSubmit()}>
          <Trans>Zapisz</Trans>
        </Button>
      </div>
    </div>
  );
};

export default UserForm;
