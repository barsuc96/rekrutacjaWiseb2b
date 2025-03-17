import React, { FC, useMemo } from 'react';
import classnames from 'classnames';
import { Trans, useTranslation } from 'react-i18next';
import { useFormik } from 'formik';
import Textarea from '@mui/material/TextareaAutosize';
import * as Yup from 'yup';

import { useGetCmsSectionFieldTypes, useDeleteCmsSectionField } from 'api';
import { ICmsSectionItemFieldItem, ICmsSectionFieldType, ICmsSectionFieldRequest } from 'api/types';
import { Input, Button, FormElement, Checkbox, Select } from 'components/controls';

import styles from 'theme/pages/Cms/components/SectionForm.module.scss';

// typ danych wejściowych
interface IProps {
  data?: ICmsSectionItemFieldItem;
  activeSection: number;
  activeSectionField: number;
  refetchSectionFieldsData: () => void;
  isLoading: boolean;
  mutate: (arg: ICmsSectionFieldRequest) => void;
}

const SectionFieldForm: FC<IProps> = ({
  mutate,
  data,
  activeSection,
  activeSectionField,
  refetchSectionFieldsData,
  isLoading
}) => {
  const { t } = useTranslation();

  const { data: typeData } = useGetCmsSectionFieldTypes();

  // kasowanie pola sekcji
  const { mutate: deleteSectionField, isLoading: isDeletingSecionField } = useDeleteCmsSectionField(
    activeSection,
    activeSectionField,
    {
      onSuccess: () => {
        refetchSectionFieldsData();
        resetForm();
      }
    }
  );

  // obsługa formularza
  const { values, errors, setFieldValue, handleSubmit, resetForm } = useFormik({
    initialValues: {
      symbol: data?.symbol || '',
      label: data?.label || '',
      description: data?.description || '',
      type: data?.type || '',
      is_active: data?.is_active || false
    },
    onSubmit: async (values) => {
      await mutate(values);
      refetchSectionFieldsData();

      if (!data) {
        resetForm();
      }
    },
    validationSchema: Yup.object().shape({
      symbol: Yup.string().required(t('Pole wymagane')),
      label: Yup.string().required(t('Pole wymagane')),
      type: Yup.string().required(t('Pole wymagane')),
      description: Yup.string(),
      is_active: Yup.bool()
    }),
    validateOnChange: false,
    enableReinitialize: true
  });

  // opcje dropdownu typów pola
  const typeSelectOptions = useMemo(() => {
    const types = typeData
      ? (typeData.items || []).map((type) => ({
          value: type.value,
          label: <span>{type.label}</span>,
          item: type
        }))
      : [];

    return types;
  }, [typeData]);

  return (
    <div
      className={classnames(styles.componentWrapper, 'StylePath-Pages-Cms-components-SectionForm')}>
      <form onSubmit={handleSubmit}>
        <div className={styles.inputs}>
          <FormElement halfWidth>
            <label>
              <Trans>Typ</Trans>
            </label>
            <Select<ICmsSectionFieldType>
              options={typeSelectOptions}
              variant="bordered"
              value={values.type}
              onChange={(item) => {
                setFieldValue('type', item?.value);
              }}
              error={errors.type}
            />
          </FormElement>
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
              value={values.label}
              onChange={(value) => setFieldValue('label', value)}
              error={errors.label}
            />
          </FormElement>
          <div className={styles.active}>
            <label>
              <Trans>Aktywny</Trans>
            </label>
            <Checkbox
              checked={values.is_active}
              onClick={() => setFieldValue('is_active', !values.is_active)}
            />
          </div>
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
        <div className={styles.actions}>
          <Button color="primary" htmlType="submit" loading={isLoading}>
            <Trans>Zapisz</Trans>
          </Button>
          {!!activeSectionField && (
            <Button
              color="danger"
              onClick={() => deleteSectionField()}
              loading={isDeletingSecionField}>
              <Trans>Usuń</Trans>
            </Button>
          )}
        </div>
      </form>
    </div>
  );
};

export default SectionFieldForm;
