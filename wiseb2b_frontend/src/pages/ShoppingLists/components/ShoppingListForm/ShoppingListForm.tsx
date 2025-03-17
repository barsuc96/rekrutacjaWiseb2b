// formalarz dodawania listy zakupowej

import React, { FC } from 'react';
import { useFormik } from 'formik';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';
import Textarea from '@mui/material/TextareaAutosize';

import { usePostShoppingList } from 'api';
import { transformApiErrorsToFormik } from 'api/helpers';
import { Button, FormElement, Input } from 'components/controls';

import styles from 'theme/pages/ShoppingLists/components/ShoppingListForm/ShoppingListForm.module.scss';

interface IProps {
  onSuccess: () => void;
  onCancel: () => void;
}

const ShoppingListForm: FC<IProps> = ({ onCancel, onSuccess }) => {
  const { t } = useTranslation();

  // tworzenie listy zakupowej
  const { mutate: createShoppingList, isLoading: isSaving } = usePostShoppingList({
    onSuccess: () => {
      onSuccess();
      onCancel();
    },
    onError: (error) => {
      setErrors(transformApiErrorsToFormik(error.error_fields));
    }
  });

  const { values, errors, setFieldValue, handleSubmit, setErrors } = useFormik({
    initialValues: {
      name: '',
      description: ''
    },
    onSubmit: (values) => {
      createShoppingList(values);
    },
    validateOnChange: false
  });

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-ShoppingLists-components-ShoppingListForm')}>
      <FormElement>
        <Input
          value={values.name}
          onChange={(value) => setFieldValue('name', value)}
          error={errors.name}
          placeholder={t('Nazwa nowej listy zakupowej')}
        />
      </FormElement>

      <FormElement>
        <Textarea
          className={styles.textarea}
          defaultValue={values.description}
          placeholder={t('Opis listy')}
          minRows={5}
          onChange={(event) => setFieldValue('description', event.target.value)}
        />
      </FormElement>

      <div className={styles.actions}>
        <Button onClick={onCancel} color="secondary" ghost>
          <Trans>Anuluj</Trans>
        </Button>
        <Button color="secondary" loading={isSaving} onClick={() => handleSubmit()}>
          <Trans>Zapisz</Trans>
        </Button>
      </div>
    </div>
  );
};

export default ShoppingListForm;
