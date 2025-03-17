// formularz tworzenia koszyka

import React, { FC, useMemo, useState } from 'react';
import { Trans, useTranslation } from 'react-i18next';
import classnames from 'classnames';

import { useGetCartsAll, usePostCartAddProductToCart } from 'api';
import { ICartListItem, IPostCartPositionsRequest } from 'api/types';
import { Button, Modal, Select } from 'components/controls';
import { AddCartForm } from 'components/containers';

import styles from 'theme/components/containers/AddPositionsToCartSelect/AddPositionsToCartSelect.module.scss';
import { CartIcon } from 'assets/icons';

// typ danych wejściowych
interface IProps {
  onSuccess: () => void;
  positions: IPostCartPositionsRequest['positions'];
}

const AddPositionsToCartSelect: FC<IProps> = ({ positions, onSuccess }) => {
  const { t } = useTranslation();

  // ID koszyka, do którego będą dodane zaznaczone pozycje
  const [updateCartId, setUpdateCartId] = useState<number | null>(null);

  // czy widoczny jest modal z tworzeniem koszyka
  const [isAddCartModal, setIsAddCartModal] = useState(false);

  // lista koszyków
  const { data: cartsData, refetch: refetchCartsData } = useGetCartsAll();

  // dodanie pozycji do koszyka
  const { mutate: addProductsToCart, isLoading: isAddProductsToCartLoading } =
    usePostCartAddProductToCart({
      onSuccess: () => {
        setUpdateCartId(null);
        setIsAddCartModal(false);
        refetchCartsData();
        onSuccess();
      }
    });

  // opcje dropdownu koszyków
  const cartSelectOptions = useMemo(() => {
    const carts = cartsData
      ? (cartsData.items || []).map((cart) => ({
          value: cart.id,
          label: (
            <>
              {cart.name} <span>({cart.products_count})</span>
            </>
          ),
          item: cart
        }))
      : [];

    return [{ value: -1, label: `-- ${t('Nowy koszyk')} --`, item: null }, ...carts];
  }, [cartsData]);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-AddPositionsToCartSelect'
      )}>
      <Select<ICartListItem>
        options={cartSelectOptions}
        variant="bordered"
        disabled={positions.length < 1}
        value={updateCartId}
        placeholder={
          <div className={styles.placeholder}>
            <CartIcon /> <Trans>Dodaj produkty do koszyka</Trans>
          </div>
        }
        onChange={(item) => {
          setUpdateCartId(item?.id || null);
          !item && setIsAddCartModal(true);
        }}
      />

      {updateCartId && (
        <Modal title={t('Potwierdzenie dodania')} onClose={() => setUpdateCartId(null)}>
          <div className={styles.confirmationModal}>
            <Trans>Czy napewno dodać wybrane produkty do koszyka?</Trans>
            <div className={styles.confirmationModalActions}>
              <Button onClick={() => setUpdateCartId(null)} ghost color="secondary">
                <Trans>Anuluj</Trans>
              </Button>
              <Button
                onClick={() =>
                  updateCartId &&
                  addProductsToCart({
                    cart_id: updateCartId,
                    products: positions
                  })
                }
                color="secondary"
                loading={isAddProductsToCartLoading}>
                <Trans>Dodaj</Trans>
              </Button>
            </div>
          </div>
        </Modal>
      )}

      {isAddCartModal && (
        <Modal title={t('Nowy koszyk')} onClose={() => setIsAddCartModal(false)}>
          <AddCartForm
            onCancel={() => setIsAddCartModal(false)}
            onSuccess={(cartId) => {
              addProductsToCart({
                cart_id: cartId,
                products: positions
              });
            }}
          />
        </Modal>
      )}
    </div>
  );
};

export default AddPositionsToCartSelect;
