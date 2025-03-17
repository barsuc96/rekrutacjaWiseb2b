// formularz dodawania do listy zakupowej

import React, { FC } from 'react';
import { useFormik } from 'formik';
import * as Yup from 'yup';
import { useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useGetShoppingLists, usePostShoppingList, usePostShoppingListPositions } from 'api';
import { IShoppingListListItem } from 'api/types';
import { Button, Select } from 'components/controls';

import styles from 'theme/components/containers/AddToShoppingListForm/AddToShoppingListForm.module.scss';

// typ danych wejściowych
interface IProps {
  items: {
    product_id: number;
    unit_id: number;
    quantity: number;
    image?: string;
    title: string;
    index: string;
    price_net_formatted?: string;
    price_gross_formatted?: string;
    currency: string;
  }[];
  onCancel: () => void;
  onSuccess?: () => void;
}

const AddToShoppingListForm: FC<IProps> = ({ onCancel, onSuccess, items }) => {
  const { t } = useTranslation();

  // obsługa danycj formularza
  const { values, errors, setFieldValue, handleSubmit } = useFormik({
    initialValues: { shoppingListId: undefined },
    validationSchema: Yup.object().shape({
      shoppingListId: Yup.number().required(
        t('Wybierz lub stwórz listę zakupową przed dodaniem produktu')
      )
    }),
    onSubmit: () => {
      addPositionsToShoppingList({
        positions: items.map((item) => ({
          product_id: item.product_id,
          unit_id: item.unit_id,
          quantity: item.quantity
        }))
      });
    }
  });

  // lista list zakupowych
  const { data: shoppingListsData, refetch: refetchShoppingListsData } = useGetShoppingLists({
    page: 1,
    limit: 999
  });

  // tworzenie listy zakupowej
  const { mutate: addShoppingList } = usePostShoppingList({
    onSuccess: (data) => {
      setFieldValue('shoppingListId', data.data.id);
      refetchShoppingListsData();
    }
  });

  // dodawanie pozycji do listy zakupowej
  const { mutate: addPositionsToShoppingList, isLoading: isAddingProducts } =
    usePostShoppingListPositions(values.shoppingListId || 0, {
      onSuccess: () => {
        onSuccess?.();
        onCancel();
      }
    });

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-AddToShoppingListForm'
      )}>
      <div className={styles.productList}>
        {items.map((item) => (
          <div className={styles.product} key={item.product_id}>
            <div className={styles.image}>
              <img src={item.image} alt="product cover" />
            </div>
            <div className={styles.description}>
              {item.title}
              <span className={styles.small}>
                {t('INDEX')}: <strong>{item.index}</strong>
              </span>
            </div>
            <div className={styles.price}>
              <strong>
                {item.price_net_formatted} {item.currency}
              </strong>{' '}
              {t('netto')}
              <span className={styles.small}>
                <strong>
                  {item.price_gross_formatted} {item.currency}
                </strong>{' '}
                {t('brutto')}
              </span>
            </div>
          </div>
        ))}
      </div>

      <div>
        <Select<IShoppingListListItem>
          placeholder={t('Wybierz listę zakupową lub wpisz nazwę nowej')}
          onChange={(shoppingList) =>
            shoppingList && setFieldValue('shoppingListId', shoppingList.id)
          }
          onCreateOption={(label: string) =>
            addShoppingList({
              name: label,
              description: ''
            })
          }
          value={values.shoppingListId}
          options={
            shoppingListsData?.items.map((shoppingList) => ({
              value: shoppingList.id,
              label: shoppingList.name,
              item: shoppingList
            })) || []
          }
          error={errors.shoppingListId}
        />

        <div className={styles.actions}>
          <Button onClick={onCancel} ghost color="secondary">
            {t('Anuluj')}
          </Button>
          <Button
            htmlType="button"
            color="secondary"
            loading={isAddingProducts}
            onClick={() => handleSubmit()}>
            {t('Dodaj do listy')}
          </Button>
        </div>
      </div>
    </div>
  );
};

export default AddToShoppingListForm;
