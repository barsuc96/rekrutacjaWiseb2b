import classnames from 'classnames';
import React, { FC, useCallback, useEffect, useMemo, useState } from 'react';
import { JournalText, ThreeDotsVertical } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';

import { IProductListItem, IUnit } from 'api/types';
import { useSelector } from 'store';
import { Availability, Counter, DropDown, Label, Link, Select } from 'components/controls';

import styles from 'theme/components/containers/MobileProductItem/MobileProductItem.module.scss';
import {
  usePostProductQuantity,
  usePostProductQuantityDecrement,
  usePostProductQuantityIncrement
} from 'api';
import AddToShoppingListButton from '../AddToShoppingListButton';
import AddToCartButton from '../AddToCartButton';

// typ danych wejściowych
interface Props {
  product: IProductListItem;
  categoryId?: number;
  searchKeywords?: string;
  line?: boolean;
  minimalVariant?: boolean;
}

const MobileProductItem: FC<Props> = ({ product, categoryId, searchKeywords }) => {
  const location = useLocation();
  const { profile } = useSelector((state) => state.auth);

  // aktualna ilość
  const [quantity, setQuantity] = useState(0);

  // aktualnie zmieniona ilość (sprzed walidacji)
  const [manuallyChangedQuantity, setManuallyChangedQuantity] = useState<number>();

  const [openModal, setOpenModal] = useState(false);

  // ID aktualnej jednostki
  const [unitId, setUnitId] = useState<number>(product.units[0]?.unit_id);

  // aktualna jednostka
  const unit = useMemo(
    () => product.units.find((unit) => unit.unit_id === unitId),
    [unitId, product]
  );

  // zmiana ilości po zmianie jednostki
  useEffect(() => {
    unit && setQuantity(unit.minimal_quantity || 1);
  }, [unit]);

  // pobranie informacji o zmniejsonej ilości
  const { mutate: decrementQuantity, isLoading: isDecrementQuantityLoading } =
    usePostProductQuantityDecrement(product.id, {
      onSuccess: (data) => {
        setQuantity(data.data.value);
      }
    });

  // pobranie informacji o zwiększonej ilości
  const { mutate: incrementQuantity, isLoading: isIncrementQuantityLoading } =
    usePostProductQuantityIncrement(product.id, {
      onSuccess: (data) => {
        setQuantity(data.data.value);
      }
    });

  // pobranie informacji ilości
  const {
    mutate: updateQuantity,
    mutateAsync: updateQuantityAsync,
    isLoading: isUpdateQuantityLoading
  } = usePostProductQuantity(product.id, {
    onSuccess: (data) => {
      setQuantity(data.data.value);
    }
  });

  // funkcja renderująca przycisk dodawania produktu do listy zakupowej
  const renderAddToShoppingListButton = useCallback(
    () => (
      <div
        className={classnames(styles.shoppingListButtonWrapper, {
          [styles.shoppingListButtonWrapperLine]: true
        })}>
        {profile?.role !== 'ROLE_OPEN_PROFILE' && (
          <AddToShoppingListButton
            openModal={openModal}
            setOpenModal={setOpenModal}
            product={product as IProductListItem}
            unit={unit}
            quantity={quantity}
          />
        )}
      </div>
    ),
    [profile, quantity, unit, openModal]
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-CartPositions-Components-MobileCart'
      )}
      itemProp="item"
      itemScope
      itemType="http://schema.org/Product">
      <Link to={`/${product.url_link}`} state={{ categoryId, searchKeywords }}>
        <meta itemProp="url" content={window.location.origin + product.url_link} />
        <img itemProp="image" src={product.images[0]?.thumb} />
      </Link>

      <div className={styles.topBar}>
        <div className={styles.labels}>
          {product?.labels.map((label) => (
            <Label key={label.type} label={label} />
          ))}
        </div>
        <DropDown
          label={<ThreeDotsVertical />}
          items={[
            {
              label: (
                <div
                  className={styles.dropdownAction}
                  onClick={() => {
                    setOpenModal(true);
                  }}>
                  <JournalText />
                  <Trans>Dodaj do listy</Trans>
                </div>
              )
            }
          ]}
          withDropdownIcon={false}
        />
      </div>
      <div className={styles.productInfo}>
        <div itemProp="name" className={styles.title}>
          {product.title}
        </div>
        <div className={styles.producer}>
          <span>INDEX:</span>
          <span>{product.index}</span>
          {product?.producer_name && (
            <>
              <span>|</span>
              <span>
                <Trans>Producent</Trans>:
              </span>
              <span itemScope itemProp="brand" itemType="http://schema.org/Brand">
                <span itemProp="name">{product?.producer_name}</span>
              </span>
            </>
          )}
        </div>
      </div>
      <div className={styles.productContent}>
        <div className={styles.priceWrapper}>
          <div>
            <div className={styles.netto}>
              <span>
                {product?.units[0].old_price_net_formatted} {product.currency}
              </span>
              <span>
                {product?.units[0].price_net_formatted} {product.currency}
              </span>
              <span>
                <Trans>netto</Trans>
              </span>
            </div>

            <div className={styles.brutto}>
              <span>
                {product?.units[0].price_gross_formatted} {product.currency}
              </span>
              <span>
                <Trans>brutto</Trans>
              </span>
            </div>
          </div>

          <div className={styles.options}>
            <span className={styles.addToList}>{renderAddToShoppingListButton()}</span>
            <span>{product?.stock && <Availability stock={product.stock} />}</span>
          </div>
        </div>
      </div>

      <div className={styles.productOrder}>
        {product.units.length > 1 && profile?.role !== 'ROLE_OPEN_PROFILE' ? (
          <Select<IUnit>
            value={unitId}
            options={product.units.map((unit) => ({
              value: unit.unit_id,
              label: `${unit.name} ${unit.converter !== 1 ? `(${unit.converter})` : ''}`,
              item: unit
            }))}
            variant="default"
            onChange={(unit) => unit?.unit_id && setUnitId(unit.unit_id)}
          />
        ) : (
          <div>
            <Trans>Jednostka</Trans>:{' '}
            <strong>
              {product?.units?.find((unit) => product.default_unit_id === unit.id)?.name}
            </strong>
          </div>
        )}
        {profile?.role !== 'ROLE_OPEN_PROFILE' ? (
          <Counter
            onChange={(value) => updateQuantity({ value, unit_id: unitId })}
            onDecrease={() => decrementQuantity({ value: quantity, unit_id: unitId })}
            onIncrease={() => incrementQuantity({ value: quantity, unit_id: unitId })}
            value={quantity}
            onChangeValue={(isChanged, newValue) => setManuallyChangedQuantity(newValue)}
            disabled={
              isDecrementQuantityLoading || isIncrementQuantityLoading || isUpdateQuantityLoading
            }
          />
        ) : (
          <Link
            to={`/login?return_url=${encodeURIComponent(location.pathname + location.search)}`}
            className={styles.loginButton}>
            <Trans>zaloguj</Trans>
          </Link>
        )}

        <AddToCartButton
          isQuantityChanges={!!manuallyChangedQuantity && manuallyChangedQuantity !== quantity}
          updateQuantity={async () => {
            const newQuantity = manuallyChangedQuantity
              ? await updateQuantityAsync({
                  value: manuallyChangedQuantity,
                  unit_id: unitId
                })
              : undefined;

            return newQuantity?.data.value === manuallyChangedQuantity
              ? manuallyChangedQuantity
              : undefined;
          }}
          quantity={quantity}
          unitId={unitId}
          productId={product.id}
          disabled={
            profile?.role === 'ROLE_OPEN_PROFILE' ||
            isDecrementQuantityLoading ||
            isIncrementQuantityLoading
          }
        />
      </div>
    </div>
  );
};

export default MobileProductItem;
