// Przyciask dodawania pozycji do koszyka

import React, { FC, useMemo, useState } from 'react';
import classnames from 'classnames';
import { Cart, ChevronDown } from 'react-bootstrap-icons';
import { useTranslation } from 'react-i18next';

import { useNotifications } from 'hooks';
import { useSelector } from 'store';
import { useGetCartsAll, usePostCartPositions } from 'api';
import { DropDown, Modal } from 'components/controls';
import { AddCartForm } from 'components/containers';

import styles from 'theme/components/containers/AddToCartButton/AddToCartButton.module.scss';

// typ danych wejściowych
interface IProps {
  isQuantityChanges?: boolean;
  quantity: number;
  unitId: number;
  productId: number;
  large?: boolean;
  disabled?: boolean;
  updateQuantity?: () => Promise<number | undefined>;
}

const AddToCartButton: FC<IProps> = ({
  quantity,
  unitId,
  productId,
  large,
  disabled,
  isQuantityChanges,
  updateQuantity
}) => {
  const { t } = useTranslation();
  const { showWarningMessage } = useNotifications();

  // domyślny koszyk
  const { currentCartId } = useSelector((state) => state.cart);

  // czy jest modal dodawania koszyka
  const [isAddCartModal, setIsAddCartModal] = useState(false);

  // pobranie lista koszyków
  const { data: cartsData, refetch: refetchCartData } = useGetCartsAll({ enabled: false });

  // dodawanie pozycji do koszyka
  const { mutate: addPositionsToCartMutate, isLoading: isAddingPositions } = usePostCartPositions({
    onSuccess: () => {
      refetchCartData();
    }
  });

  // funkcja wrapująca dodawanie pozycji do koszyka
  const addPositionsToCart = async (cartId: number) => {
    const validatedQuantity = isQuantityChanges ? await updateQuantity?.() : quantity;

    !validatedQuantity
      ? showWarningMessage(t('Produkt nie został dodany. Ilość została zaktualizowana'))
      : addPositionsToCartMutate({
          cartId,
          positions: [
            {
              product_id: productId,
              unit_id: unitId,
              quantity: validatedQuantity || quantity
            }
          ]
        });
  };

  // momoizowana lista koszyków (opcje dropdownu) - renderowana przy zmianie cartsData
  const items = useMemo(() => {
    const carts =
      cartsData?.items.map((cart) => ({
        label: cart.name,
        onClick: () => addPositionsToCart(cart.id)
      })) || [];

    return [
      { label: `-- ${t('Nowy koszyk')} --`, onClick: () => setIsAddCartModal(true) },
      ...carts
    ];
  }, [cartsData, quantity]);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        {
          [styles.large]: large,
          [styles.disabled]: disabled
        },
        'StylePath-Components-Containers-AddToCartButton'
      )}>
      <button
        disabled={disabled}
        className={styles.cart}
        onClick={() =>
          currentCartId ? addPositionsToCart(currentCartId) : setIsAddCartModal(true)
        }>
        {!!large && t('Do koszyka')}
        <Cart />
      </button>

      <div className={styles.dropdown}>
        <DropDown
          disabled={disabled || isAddingPositions}
          items={items}
          label={<ChevronDown />}
          withDropdownIcon={false}
        />
      </div>

      {isAddCartModal && (
        <Modal title={t('Nowy koszyk')} onClose={() => setIsAddCartModal(false)}>
          <AddCartForm
            onCancel={() => setIsAddCartModal(false)}
            onSuccess={(cartId: number) => {
              addPositionsToCart(cartId);
            }}
          />
        </Modal>
      )}
    </div>
  );
};

export default AddToCartButton;
