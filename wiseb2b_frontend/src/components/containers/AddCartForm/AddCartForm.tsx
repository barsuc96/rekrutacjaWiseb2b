// formularz tworzenia koszyka

import React, { FC } from 'react';
import { useTranslation } from 'react-i18next';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import classnames from 'classnames';

import { useGetCartsAll, usePostCart } from 'api';
import { Button, Input } from 'components/controls';

import styles from 'theme/components/containers/AddCartForm/AddCartForm.module.scss';

// typ danych wejściowych
interface IProps {
  onCancel: () => void;
  onSuccess: (cartId: number) => void;
}

const AddCartForm: FC<IProps> = ({ onCancel, onSuccess }) => {
  const { t } = useTranslation();

  // obsługa formularzs
  const { values, errors, setFieldValue, handleSubmit } = useFormik({
    initialValues: { symbol: '' },
    validationSchema: Yup.object().shape({
      symbol: Yup.string().required(t('Pole wymagane'))
    }),
    onSubmit: (values) => {
      createCart({ symbol: values.symbol.trim() });
    }
  });

  // lista koszyków - potrzebna do odświeżenia po dodaniu nowego
  const { refetch: refetchCarts } = useGetCartsAll({ enabled: false });

  // dodawanie koszyka
  const { mutate: createCart, isLoading: isSaving } = usePostCart({
    onSuccess: async ({ data: { id } }) => {
      await onSuccess(id);
      await refetchCarts();
      onCancel();
    }
  });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-AddCartForm'
      )}>
      <Input
        value={values.symbol}
        onChange={(value) => setFieldValue('symbol', value)}
        type="text"
        placeholder={t('Nazwa koszyka')}
        error={errors.symbol}
      />
      <div className={styles.actions}>
        <Button onClick={onCancel} ghost color="secondary">
          {t('Anuluj')}
        </Button>

        <Button loading={isSaving} onClick={() => handleSubmit()} color="secondary">
          {t('Utwórz')}
        </Button>
      </div>
    </div>
  );
};

export default AddCartForm;
