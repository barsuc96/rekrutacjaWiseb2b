// Produkt wyświetlany na listach i sliderach

import React, { useState, FC, useMemo, useCallback, useEffect } from 'react';
import classnames from 'classnames';
import { Check2, X } from 'react-bootstrap-icons';
import { Trans, useTranslation } from 'react-i18next';
import { useLocation } from 'react-router-dom';

import { useSelector } from 'store';
import {
  usePostProductQuantityDecrement,
  usePostProductQuantityIncrement,
  usePostProductQuantity
} from 'api';
import { IProductListItem, ITechnicalAttribute, IUnit } from 'api/types';
import { AddToCartButton, AddToShoppingListButton } from 'components/containers';
import { Select, Availability, Counter, Link, Label } from 'components/controls';

import styles from 'theme/components/containers/ProductItem/ProductItem.module.scss';

// typ danych wejściowych
interface Props {
  product: IProductListItem;
  categoryId?: number;
  searchKeywords?: string;
  line?: boolean;
  minimalVariant?: boolean;
  imageStretch?: boolean;
  slide?: boolean;
  isSearch?: boolean;
}

const ProductItem: FC<Props> = ({
  product,
  line,
  minimalVariant,
  imageStretch,
  slide,
  categoryId,
  searchKeywords,
  isSearch
}) => {
  const { t } = useTranslation();
  const location = useLocation();
  const { profile } = useSelector((state) => state.auth);

  // czy rozwinięte są szczegóły
  const [areProductDetails, setAreProductDetails] = useState(false);

  // aktualna ilość
  const [quantity, setQuantity] = useState(0);

  // aktualnie zmieniona ilość (sprzed walidacji)
  const [manuallyChangedQuantity, setManuallyChangedQuantity] = useState<number>();

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

  // funkcja renderująca dropdown jednostek
  const renderUnitSelector = useCallback(() => {
    const unit = product.units.find((unit) => unitId === unit.unit_id);
    return (
      <div
        className={classnames(styles['unit-selector-wrapper'], {
          [styles.unitSelectorWrapperLine]: !!line,
          [styles.unitSelectorWrapperMinimal]: !!minimalVariant
        })}>
        {product.units.length > 1 && profile?.role !== 'ROLE_OPEN_PROFILE' ? (
          <Select<IUnit>
            onChange={(unit) => unit?.unit_id && setUnitId(unit.unit_id)}
            value={unitId}
            options={product.units.map((unit) => ({
              value: unit.unit_id,
              label: `${unit.name} ${unit.converter !== 1 ? `(${unit.converter})` : ''}`,
              item: unit
            }))}
          />
        ) : (
          <>
            <Trans>Jednostka</Trans>: <strong>{unit?.name}</strong>
          </>
        )}
      </div>
    );
  }, [unitId, product]);

  // funkcja renderująca przycisk dodawania produktu do listy zakupowej
  const renderAddToShoppingListButton = useCallback(
    () => (
      <div
        className={classnames(styles.shoppingListButtonWrapper, {
          [styles.shoppingListButtonWrapperLine]: !!line
        })}>
        {profile?.role !== 'ROLE_OPEN_PROFILE' && (
          <AddToShoppingListButton
            product={product as IProductListItem}
            unit={unit}
            quantity={quantity}
          />
        )}
      </div>
    ),
    [profile, quantity, unit]
  );

  // funkcja renderująca wartość atrybutu
  const renderTechnicalAttributeValue = (item: ITechnicalAttribute) =>
    item.type === 'boolean' ? (
      item.value ? (
        <Check2 className={styles.green} />
      ) : (
        <X className={styles.red} />
      )
    ) : (
      item.value
    );

  const Title = () => {
    return (
      <div
        className={classnames(styles['product-title'], {
          [styles.productTitleLine]: !!line
        })}>
        <Link
          className={styles.title}
          to={`/${product.url_link}`}
          state={{ categoryId, searchKeywords }}>
          <span itemProp="name">{product.title}</span>
        </Link>
        <meta itemProp="url" content={window.location.origin + product.url_link} />
        <span>
          {t('INDEX')}: {product.index}
        </span>
        <span>
          {product.producer_name && (
            <span itemScope itemProp="brand" itemType="http://schema.org/Brand">
              {t('Producent')}: <strong itemProp="name">{product.producer_name}</strong>
            </span>
          )}
        </span>

        {line && (
          <>
            <div
              className={styles['product-description']}
              dangerouslySetInnerHTML={{ __html: product.description }}
            />

            <div className={styles['product-params']}>
              {areProductDetails && (
                <ul>
                  {product.technical_attributes.map((attribute) => (
                    <li key={attribute.name}>
                      {attribute.name}: <strong>{renderTechnicalAttributeValue(attribute)}</strong>
                    </li>
                  ))}
                </ul>
              )}

              <button onClick={() => setAreProductDetails(!areProductDetails)}>
                {areProductDetails ? t('Zwiń szczegóły') : t('Rozwiń szczegóły')}
              </button>
            </div>
          </>
        )}

        {minimalVariant && (
          <div
            className={classnames(styles.priceWrapper, {
              [styles.priceWrapperLine]: !!line
            })}>
            {unit?.old_price_net_formatted && (
              <div
                className={classnames(styles.old, {
                  [styles.oldLine]: !!line
                })}>
                {unit.old_price_net_formatted} {product.currency}
              </div>
            )}
            <div className={styles.net}>
              <strong>
                {unit?.price_net_formatted} {product.currency}
              </strong>{' '}
              {t('netto')}
            </div>
            <div className={styles.gross}>
              {unit?.price_gross_formatted} {product.currency} {t('brutto')}
            </div>
          </div>
        )}
      </div>
    );
  };

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        {
          [styles.lineWrapper]: !!line,
          [styles.slide]: !!slide
        },
        'StylePath-Components-Containers-ProductItem'
      )}
      itemProp="item"
      itemScope
      itemType="http://schema.org/Product">
      <div
        className={classnames(styles.imageTitleWrapper, { [styles.imageStretch]: imageStretch })}>
        <Link
          to={`/${product.url_link}`}
          state={{ categoryId, searchKeywords }}
          className={classnames(styles['product-image'], {
            [styles.productImageLine]: !!line,
            [styles.productImageMinimal]: !!minimalVariant
          })}
          style={{ backgroundImage: `url(${product.images[0]?.min})` }}>
          {product.labels.map((label) => (
            <Label key={label.type} label={label} />
          ))}
        </Link>
        <meta itemProp="image" content={product.images[0]?.min} />
        {minimalVariant && <Title />}
      </div>

      <div
        className={classnames(styles['product-details'], {
          [styles.productDetailsLine]: !!line,
          [styles.noActions]: profile?.role === 'ROLE_OPEN_PROFILE'
        })}>
        {!minimalVariant && <Title />}

        <div
          className={classnames(styles['product-actions'], {
            [styles.productActionsLine]: !!line,
            [styles.isSearch]: !!isSearch
          })}>
          {!line && !minimalVariant && renderUnitSelector()}

          {profile?.role !== 'ROLE_OPEN_PROFILE' && !minimalVariant && (
            <div
              className={classnames(styles.priceWrapper, {
                [styles.priceWrapperLine]: !!line
              })}>
              {unit?.old_price_net_formatted && (
                <div
                  className={classnames(styles.old, {
                    [styles.oldLine]: !!line
                  })}>
                  {unit.old_price_net_formatted} {product.currency}
                </div>
              )}
              <div className={styles.net}>
                <strong>
                  {unit?.price_net_formatted} {product.currency}
                </strong>{' '}
                {t('netto')}
              </div>
              <div className={styles.gross}>
                {unit?.price_gross_formatted} {product.currency} {t('brutto')}
              </div>
            </div>
          )}

          {!minimalVariant && (
            <div
              className={classnames(styles['availability-wrapper'], {
                [styles.availabilityWrapperLine]: !!line
              })}>
              {line && renderAddToShoppingListButton()}
              <Availability stock={product.stock} />
            </div>
          )}

          <div
            className={classnames(styles.tools, {
              [styles.toolsLine]: !!line
            })}>
            {(line || minimalVariant) && renderUnitSelector()}

            <div className={styles['counter-wrapper']}>
              {profile?.role !== 'ROLE_OPEN_PROFILE' ? (
                <Counter
                  onChange={(value) => updateQuantity({ value, unit_id: unitId })}
                  onDecrease={() => decrementQuantity({ value: quantity, unit_id: unitId })}
                  onIncrease={() => incrementQuantity({ value: quantity, unit_id: unitId })}
                  value={quantity}
                  onChangeValue={(isChanged, newValue) => setManuallyChangedQuantity(newValue)}
                  disabled={
                    isDecrementQuantityLoading ||
                    isIncrementQuantityLoading ||
                    isUpdateQuantityLoading
                  }
                />
              ) : (
                <Link
                  to={`/login?return_url=${encodeURIComponent(
                    location.pathname + location.search
                  )}`}
                  className={styles.loginButton}>
                  <Trans>zaloguj</Trans>
                </Link>
              )}
            </div>

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

          {!line && !minimalVariant && renderAddToShoppingListButton()}
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
