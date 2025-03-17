import React, { FC } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { useFormik } from 'formik';
import Textarea from '@mui/material/TextareaAutosize';
import * as Yup from 'yup';

import { ICmsSectionResponse, ICmsSectionRequest } from 'api/types';

import { Input, Button, FormElement, Checkbox } from 'components/controls';

import styles from 'theme/pages/Cms/components/SectionForm.module.scss';

// typ danych wejściowych
interface IProps {
  onClose?: () => void;
  activeSection?: number;
  data?: ICmsSectionResponse;
  isLoading: boolean;
  mutate: (arg: ICmsSectionRequest) => void;
}

const SectionForm: FC<IProps> = ({ onClose, mutate, data, isLoading }) => {
  const { t } = useTranslation();

  const isEditSectionForm = !!data;

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit } = useFormik({
    initialValues: {
      symbol: data?.symbol || '',
      name: data?.name || '',
      description: data?.description || '',
      is_active: data?.is_active || false
    },
    onSubmit: (values) => {
      mutate(values);
    },
    validationSchema: Yup.object().shape({
      symbol: Yup.string().required(t('Pole wymagane')),
      name: Yup.string().required(t('Pole wymagane')),
      description: Yup.string(),
      is_active: Yup.bool()
    }),
    validateOnChange: false,
    enableReinitialize: true
  });

  return (
    <div
      className={classnames(styles.componentWrapper, 'StylePath-Pages-Cms-components-SectionForm')}>
      <form onSubmit={handleSubmit}>
        <div className={styles.inputs}>
          <FormElement halfWidth>
            <label>
              <Trans>Symbol</Trans>
            </label>
            <Input
              value={values.symbol}
              onChange={(value) => setFieldValue('symbol', value)}
              error={errors.symbol}
            />
          </FormElement>
          <FormElement halfWidth>
            <label>
              <Trans>Nazwa</Trans>
            </label>
            <Input
              value={values.name}
              onChange={(value) => setFieldValue('name', value)}
              error={errors.name}
            />
          </FormElement>
          <FormElement>
            <label>
              <Trans>Opis</Trans>
            </label>
            <Textarea
              value={values.description}
              onChange={({ target: { value } }) => setFieldValue('description', value)}
              minRows={5}
            />
          </FormElement>
        </div>
        <div className={styles.active}>
          <label>
            <Trans>Aktywny</Trans>
          </label>
          <Checkbox
            checked={values.is_active}
            onClick={() => setFieldValue('is_active', !values.is_active)}
          />
        </div>
        <div className={styles.actions}>
          {!isEditSectionForm && (
            <Button color="secondary" ghost onClick={onClose}>
              <Trans>Anuluj</Trans>
            </Button>
          )}
          <Button color="primary" htmlType="submit" loading={isLoading}>
            <Trans>Zapisz</Trans>
          </Button>
        </div>
      </form>
    </div>
  );
};

export default SectionForm;
