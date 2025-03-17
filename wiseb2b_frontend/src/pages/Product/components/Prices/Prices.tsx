// box z cenami, jedostkami itp

import React, { FC, useEffect, useMemo, useState } from 'react';
import classnames from 'classnames';
import { Printer, Truck } from 'react-bootstrap-icons';
import { Trans } from 'react-i18next';
import { useLocation } from 'react-router-dom';

import { useSelector } from 'store';
import { IProduct } from 'api/types';
import {
  useGetProductAddToCartHint,
  useGetCartDeliveryInfo,
  usePostProductQuantityDecrement,
  usePostProductQuantityIncrement,
  usePostProductQuantity
} from 'api';
import { useAppNavigate, useNotifications } from 'hooks';
import { Counter, Radio, Availability, Link } from 'components/controls';
import { AddToCartButton, AddToShoppingListButton } from 'components/containers';

import { ReactComponent as MessageQuestionIcon } from 'assets/messages-question.svg';
import styles from 'theme/pages/Product/components/Prices/Prices.module.scss';

// typ danych wejściowych
interface IProps {
  product: IProduct;
}

const Prices: FC<IProps> = ({ product }) => {
  const location = useLocation();
  const { showWarningMessage } = useNotifications();
  const navigate = useAppNavigate();
  const { profile } = useSelector((state) => state.auth);

  // id domyślnego koszyka
  const { currentCartId } = useSelector((state) => state.cart);

  // ilość produktów
  const [quantity, setQuantity] = useState(0);

  // aktualnie zmieniona ilość (sprzed walidacji)
  const [manuallyChangedQuantity, setManuallyChangedQuantity] = useState<number>();

  // aktualne ID jednostki
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

  // pobranie informacji o zwiększonej ilości
  const { mutate: updateQuantity, mutateAsync: updateQuantityAsync } = usePostProductQuantity(
    product.id,
    {
      onSuccess: (data) => {
        setQuantity(data.data.value);
      }
    }
  );

  // pobranie informacji z podpowiedzią do koszyka
  const { data: addToCartHintData } = useGetProductAddToCartHint(
    product.id,
    {
      unit_id: unitId,
      quantity
    },
    { keepPreviousData: true }
  );

  // pobranie podpowiedzi o dostawie
  const { data: cartDeliveryInfoData } = useGetCartDeliveryInfo(currentCartId || 0, {
    enabled: !!currentCartId
  });

  const isOpenProfile = profile?.role === 'ROLE_OPEN_PROFILE';

  return (
    <div
      className={classnames(styles.wrapperComponent, 'StylePath-Pages-Product-components-Prices')}>
      <div className={styles.mainContent}>
        {!isOpenProfile && (
          <div className={styles.price}>
            <div className={styles.text}>
              <Trans>Twoja cena:</Trans>
            </div>

            <div className={styles.groupPrice}>
              <div className={styles.net}>
                {unit?.old_price_net_formatted && (
                  <div className={styles.old}>
                    {unit.old_price_net_formatted} {product.currency}
                  </div>
                )}
                <strong>
                  {unit?.price_net_formatted} {product.currency}
                </strong>{' '}
                <Trans>netto</Trans>
              </div>
              <div className={styles.gross}>
                {unit?.price_gross_formatted} {product.currency} <Trans>brutto</Trans>
              </div>
            </div>
          </div>
        )}

        {!isOpenProfile && (
          <div className={styles.tableWrapper}>
            <table>
              <thead>
                <tr>
                  <th className={styles.left}>
                    <Trans>Typ opakowania</Trans>
                  </th>
                  <th>
                    <Trans>Ilość</Trans>{' '}
                    <span>
                      <Trans>szt. w opakowaniu</Trans>
                    </span>
                  </th>
                  <th>
                    <Trans>Cena</Trans>{' '}
                    <span>
                      (<Trans>netto</Trans>)
                    </span>
                  </th>
                  <th>
                    <Trans>Cena za szt.</Trans>{' '}
                    <span>
                      (<Trans>netto</Trans>)
                    </span>
                  </th>
                </tr>
              </thead>
              <tbody>
                {product.units.map((unit) => (
                  <tr key={unit.id} className={classnames({ current: unit.unit_id === unitId })}>
                    <td className={styles.left}>
                      <span className={styles.nameWrapper}>
                        <Radio
                          checked={unit.unit_id === unitId}
                          onClick={() => setUnitId(unit.unit_id)}
                        />{' '}
                        {unit.name}
                      </span>
                    </td>
                    <td>{unit.converter}</td>
                    <td>
                      {unit.price_net_formatted} {product.currency}
                    </td>
                    <td>
                      {unit.unit_price_net_formatted} {product.currency}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {!!addToCartHintData && addToCartHintData.hint && (
          <div
            className={styles.promotion}
            style={{ borderColor: addToCartHintData.color, color: addToCartHintData.color }}>
            {addToCartHintData.hint}
          </div>
        )}

        <div className={styles.actionsWrapper}>
          {isOpenProfile ? (
            <Link
              to={`/login?return_url=${encodeURIComponent(location.pathname + location.search)}`}
              className={styles.loginButton}>
              <Trans>zaloguj</Trans>
            </Link>
          ) : (
            <Counter
              className={styles.quantityCounter}
              onChange={(value) => updateQuantity({ value, unit_id: unitId })}
              onDecrease={() => decrementQuantity({ value: quantity, unit_id: unitId })}
              onIncrease={() => incrementQuantity({ value: quantity, unit_id: unitId })}
              value={quantity}
              onChangeValue={(isChanged, newValue) => setManuallyChangedQuantity(newValue)}
            />
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
            large
            disabled={isOpenProfile || isDecrementQuantityLoading || isIncrementQuantityLoading}
          />
        </div>
      </div>

      <div className={styles.dashedBlock}>
        <div className={styles.item}>
          <Availability stock={product.stock} />
        </div>

        {cartDeliveryInfoData?.info && (
          <div className={styles.item}>
            <Truck />
            {cartDeliveryInfoData.info}
          </div>
        )}
      </div>

      <div className={styles.dashedBlock}>
        <div
          className={classnames(styles.item, styles.button)}
          onClick={() => showWarningMessage('Funkcja niezaimplementowana')}>
          <Printer />
          <Trans>Drukuj</Trans>
        </div>

        {!isOpenProfile && (
          <div className={styles.item}>
            <AddToShoppingListButton
              product={product as IProduct}
              quantity={quantity}
              unit={unit}
            />
          </div>
        )}
        <div className={classnames(styles.item, styles.button)} onClick={() => navigate('/wip')}>
          <MessageQuestionIcon />
          <Trans>Zapytaj o produkt</Trans>
        </div>
      </div>
    </div>
  );
};

export default Prices;
