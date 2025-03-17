import React, { useMemo, useState } from 'react';
import classnames from 'classnames';
import { JournalText, ThreeDotsVertical, Trash } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';
import { t } from 'i18next';

import { Availability, DropDown, Label, Loader, Select } from 'components/controls';
import { ICartPositionListItem, IPositionUnit, IProduct } from 'api/types';
import { useGetCartsAll, useGetProduct } from 'api';

import QuantityWrapper from './QuantityWrapper';

import { CartIcon } from 'assets/icons';
import styles from 'theme/components/containers/CartPositions/components/MobileCart.module.scss';
import AddToShoppingListButton from 'components/containers/AddToShoppingListButton';

interface IFeatures {
  setItemsToShoppingList: (items: ICartPositionListItem[]) => void;
  setItemsToRemove: (items: ICartPositionListItem[]) => void;
  updatingPositionIds: number[];
  handleChangeUnit: (unitId: number, position: ICartPositionListItem) => Promise<void>;
}

type Props = {
  item: ICartPositionListItem;
  features: IFeatures;
  cartId: number;
  onChange?: () => void;
  queryParams: object;
};

const MobileCart = ({
  item,
  cartId,
  onChange,
  queryParams,
  features: { setItemsToRemove, updatingPositionIds, handleChangeUnit }
}: Props) => {
  // Pobranie produktu
  const { data: productData, isLoading: isProductLoading } = useGetProduct(item.product_id, {
    enabled: !!item.product_id
  });

  const [openModal, setOpenModal] = useState(false);
  // pobranie lista koszyków
  const { data: cartsData } = useGetCartsAll();

  // momoizowana lista koszyków (opcje dropdownu) - renderowana przy zmianie cartsData
  const items = useMemo(() => {
    const carts =
      cartsData?.items.map((cart) => ({
        label: cart.name,
        onClick: () => alert('funkcja niezaimplementowana')
      })) || [];

    return [
      { label: `-- ${t('Nowy koszyk')} --`, onClick: () => alert('funkcja niezaimplementowana') },
      ...carts
    ];
  }, [cartsData]);

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-CartPositions-Components-MobileCart'
      )}>
      {isProductLoading && <Loader />}
      <div className={styles.topBar}>
        <div className={styles.labels}>
          {productData?.labels.map((label) => (
            <Label key={label.type} label={label} />
          ))}
        </div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: (
                <div
                  onClick={() => {
                    setOpenModal(true);
                  }}
                  className={classnames(styles.dropdownAction, styles.cart)}>
                  <JournalText />
                  <Trans>Dodaj do listy</Trans>
                </div>
              )
            },
            {
              label: (
                <div
                  onClick={() => setItemsToRemove([item])}
                  className={classnames(styles.dropdownAction, styles.bin)}>
                  <Trash /> <Trans>Usuń z koszyka</Trans>{' '}
                </div>
              )
            }
          ]}
          withDropdownIcon={false}
        />
      </div>
      <div className={styles.productInfo}>
        <div className={styles.title}>{item.name}</div>
        <div className={styles.producer}>
          <span>INDEX:</span>
          <span>{item.index}</span>
          {productData?.producer_name && (
            <>
              <span>|</span>
              <span>
                <Trans>Producent</Trans>:
              </span>
              <span>{productData?.producer_name}</span>
            </>
          )}
        </div>
      </div>
      <div className={styles.productContent}>
        <img src={item.image[0].thumb} />

        <div className={styles.priceWrapper}>
          <div>
            <div className={styles.netto}>
              <span>
                {productData?.units[0].old_price_net_formatted} {item.currency}
              </span>
              <span>
                {productData?.units[0].price_net_formatted} {item.currency}
              </span>
              <span>
                <Trans>netto</Trans>
              </span>
            </div>

            <div className={styles.brutto}>
              <span>
                {productData?.units[0].price_gross_formatted} {item.currency}
              </span>
              <span>
                <Trans>brutto</Trans>
              </span>
            </div>
          </div>

          <div className={styles.options}>
            <span className={styles.addToList}>
              <AddToShoppingListButton
                openModal={openModal}
                setOpenModal={setOpenModal}
                product={productData as IProduct}
                unit={productData?.units[0]}
                quantity={item.quantity}
              />
            </span>
            <span>{productData?.stock && <Availability stock={productData.stock} />}</span>
          </div>
        </div>
      </div>

      <div className={styles.productOrder}>
        {item.units.length > 1 ? (
          <Select<IPositionUnit>
            value={item.unit_id.toString()}
            options={item.units.map((unit) => ({
              value: unit.id,
              label: unit.name,
              item: unit
            }))}
            variant="default"
            onChange={(unit) => handleChangeUnit(unit?.id || 0, item)}
            disabled={updatingPositionIds.some((updatingPosition) => item.id === updatingPosition)}
          />
        ) : (
          <div>
            <Trans>Jednostka</Trans>:{' '}
            <strong>{item?.units?.find((unit) => item.unit_id === unit.unit_id)?.name}</strong>
          </div>
        )}

        <QuantityWrapper
          cartId={cartId}
          item={item}
          onChange={onChange}
          queryParams={queryParams}
        />

        <DropDown bordered label={<CartIcon />} items={items} />
      </div>
    </div>
  );
};

export default MobileCart;
