// przycisk z listą podglądu kkoszyków

import React, { useMemo, useState, useEffect } from 'react';
import classnames from 'classnames';
import Popover from '@mui/material/Popover';
import Badge from '@mui/material/Badge';
import { useTranslation } from 'react-i18next';
import { ArrowRight, Cart, ChevronDown, ChevronLeft, ChevronRight } from 'react-bootstrap-icons';

import { reduxActions, useDispatch, useSelector } from 'store';
import { useGetCartsAll } from 'api';
import { Availability, Link, Modal } from 'components/controls';
import { CartPositions } from 'components/containers';

import styles from 'theme/components/containers/CartsButton/CartsButton.module.scss';
import { useRWD } from 'hooks';

const ButtonCart = () => {
  const { t } = useTranslation();
  const dispatch = useDispatch();
  const { isMobile } = useRWD();

  // czy jest ustawiony element HTML popovera (użyte przy pokazywaniu modala)
  const [popoverAnchor, setPopoverAnchor] = useState<HTMLDivElement | null>(null);

  // ID domyślnego koszyka
  const { currentCartId } = useSelector((state) => state.cart);

  // pobranie listy koszyków
  const {
    data: cartsData,
    refetch: refetchCartsData,
    isFetching: isCartsFetching
  } = useGetCartsAll({
    onSuccess: (data) => {
      !currentCartId && dispatch(reduxActions.setCurrentCartId(data.items[0]?.id) || null);
    }
  });

  // domyślny koszyk
  const currentCart = useMemo(
    () => cartsData?.items.find((cart) => currentCartId === cart.id),
    [currentCartId, cartsData]
  );

  // index domyślnego koszyka na lisście koszyków (potrzebne do zmiany na następny/poprzedni)
  const currentCartIndex = useMemo(
    () => cartsData?.items.findIndex((cart) => currentCartId === cart.id),
    [currentCartId, cartsData]
  );

  // ustaniwienie poprzedniego koszyka jako domyślny
  const prevCart = () => {
    const prevCartId =
      typeof currentCartIndex === 'number' && cartsData?.items[currentCartIndex - 1]?.id;
    typeof prevCartId === 'number' && dispatch(reduxActions.setCurrentCartId(prevCartId));
  };

  // ustaniwienie następnefo koszyka jako domyślny
  const nextCart = () => {
    const nextCartId =
      typeof currentCartIndex === 'number' && cartsData?.items[currentCartIndex + 1]?.id;
    typeof nextCartId === 'number' && dispatch(reduxActions.setCurrentCartId(nextCartId));
  };

  // pobieranie danych przy otwarciu popovera
  useEffect(() => {
    if (currentCart?.id) {
      refetchCartsData();
    }
  }, [currentCart?.id]);

  // ustawienie domyślnego koszyka po pobraniu/aktualizacji listy koszyków
  useEffect(() => {
    !isCartsFetching &&
      !currentCart &&
      dispatch(reduxActions.setCurrentCartId(cartsData?.items[0]?.id || null));
  }, [isCartsFetching, currentCart, cartsData]);

  useEffect(() => {
    if (currentCartId) {
      dispatch(reduxActions.setCurrentCartId(currentCartId));
    }
  }, [currentCartId]);

  const renderCartContent = () => {
    return (
      <>
        <div className={styles.cartWrapper}>
          <div className={styles.cartHeader}>
            <ChevronLeft
              className={classnames(styles.arrow, { disabled: (currentCartIndex || 0) <= 0 })}
              onClick={prevCart}
            />
            {currentCart?.name}
            <ChevronRight
              className={classnames(styles.arrow, {
                disabled: (currentCartIndex || 0) >= (cartsData?.items.length || 0) - 1
              })}
              onClick={nextCart}
            />
          </div>
          <div className={styles.cartBody}>
            {currentCartId && (currentCart?.products_count || 0) > 0 ? (
              <CartPositions
                cartId={currentCartId}
                variant="small"
                onChange={() => refetchCartsData()}
              />
            ) : (
              <div className={styles.emptyCart}>
                <span className={styles.title}>{t('Twój koszyk jest pusty')}</span>
                {t('Nie przegap okazji i sprawdź dostępne produkty')}!
              </div>
            )}
          </div>

          {(currentCart?.products_count || 0) > 0 && (
            <div className={styles.cartFooter}>
              <div className={styles.total}>
                <span className={styles.label}>{t('Suma')}:</span>
                <div className={styles.prices}>
                  <span className={styles.gross}>
                    <span>
                      {currentCart?.value_gross_formatted} {currentCart?.currency}
                    </span>{' '}
                    {t('brutto')}
                  </span>
                  <span className={styles.net}>
                    <span>
                      {currentCart?.value_net_formatted} {currentCart?.currency}
                    </span>{' '}
                    {t('netto')}
                  </span>
                </div>
              </div>

              <div className={styles.actions} onClick={() => setPopoverAnchor(null)}>
                <Link to={`/cart/${currentCartId}`}>{t('Zobacz cały koszyk')}</Link>
                <Link to={`/checkout/${currentCartId}`} className={styles.primary}>
                  {t('Przejdź do kasy')}
                </Link>
              </div>
            </div>
          )}
        </div>
        {currentCartId && (currentCart?.products_count || 0) > 0 ? (
          <div className={styles.info}>
            <Availability
              stock={{
                name: (
                  <span className={styles.freeShipping}>
                    Darmowa dostawa od <strong>100zł</strong>
                  </span>
                ),
                value: 4,
                type: 'big-stock'
              }}
            />
          </div>
        ) : (
          <Link className={styles.info} to="/products">
            Zobacz produkty <ArrowRight />
          </Link>
        )}
      </>
    );
  };

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-CartsButton'
      )}>
      <Badge
        color="secondary"
        badgeContent={0}
        anchorOrigin={{
          vertical: 'top',
          horizontal: 'left'
        }}>
        <div
          className={classnames(styles.button, { [styles.active]: popoverAnchor })}
          onClick={(event) => setPopoverAnchor(event.currentTarget)}>
          {isMobile ? (
            <div>
              <Cart className={styles.icon} />
            </div>
          ) : (
            <>
              <Cart className={styles.icon} />

              {currentCart && (
                <div className={styles.detailsCart}>
                  <div className={styles.countCarts}>{currentCart.name}</div>

                  <div className={styles.price}>
                    {currentCart.positions_value_gross_formatted} {currentCart.currency}
                  </div>
                </div>
              )}
              <ChevronDown className={classnames('arrow', { rotated: !!popoverAnchor })} />
            </>
          )}
        </div>
      </Badge>
      {isMobile ? (
        !!popoverAnchor && (
          <Modal title={t('Koszyk')} onClose={() => setPopoverAnchor(null)} fullScreen>
            {renderCartContent()}
          </Modal>
        )
      ) : (
        <Popover
          anchorEl={popoverAnchor}
          open={!!popoverAnchor}
          onClose={() => setPopoverAnchor(null)}
          transformOrigin={{
            vertical: 'top',
            horizontal: 'right'
          }}
          anchorOrigin={{
            vertical: 'bottom',
            horizontal: 'right'
          }}>
          {renderCartContent()}
        </Popover>
      )}
    </div>
  );
};

export default ButtonCart;
