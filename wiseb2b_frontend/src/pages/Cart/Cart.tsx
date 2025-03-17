// strona koszyka

import React, { useEffect, useMemo, useState } from 'react';
import { useParams } from 'react-router-dom';
import { Trans, useTranslation } from 'react-i18next';
import { CreditCard } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useAppNavigate, useNotifications } from 'hooks';
import { useSelector } from 'store';
import { useGetCart, useGetCartsAll, useGetCartPaymentsExpired } from 'api';
import { Alert, Container, CheckoutSteps, Link } from 'components/controls';
import { CartPositions, CartSummary } from 'components/containers';
import { ActionBar } from './components';

import styles from 'theme/pages/Cart/Cart.module.scss';
import { ArrowIcon } from 'assets/icons';

const Cart = () => {
  const { t } = useTranslation();
  const navigate = useAppNavigate();
  const { showErrorMessage } = useNotifications();
  const { currentCartId } = useSelector((state) => state.cart);

  // ID aktualnego koszyka z url'a
  const { id } = useParams();
  // przekształcenie w numer
  const cartId = useMemo(() => parseInt(id || ''), [id]);

  // fraza wyszukiwania
  const [searchQuery, setSearchQuery] = useState('');

  // pobranie informacji o przedawnionych płatności - potrzebne do wyświetlenia alertu
  const { data: paymentsExpiredData } = useGetCartPaymentsExpired(cartId);

  // pobranie listy koszyków
  const { data: cartsData, refetch: refetchCartsData } = useGetCartsAll({
    enabled: false,
    onSuccess: (data) => {
      const currentCartItem = data.items.find((item) => item.id === cartId);
      const firstCartId = data?.items[0]?.id;
      const secondCartId = data?.items[0]?.id;
      currentCartItem
        ? refetchCartData()
        : navigate(
            firstCartId && firstCartId !== cartId
              ? `/cart/${firstCartId}`
              : secondCartId && secondCartId !== cartId
              ? `/cart/${secondCartId}`
              : '/'
          );
    }
  });

  // odświeżanie szczegółów koszyka
  const { refetch: refetchCartData } = useGetCart(cartId, {
    enabled: false
  });

  useEffect(() => {
    if (currentCartId) {
      navigate(`/cart/${currentCartId}`);
    }
  }, [currentCartId]);

  const currentCart = useMemo(
    () => cartsData?.items.find((item) => item.id === cartId),
    [cartsData, cartId]
  );

  return (
    <div className={classnames(styles.componentWrapper, 'StylePath-Pages-Cart')}>
      <Container>
        <div className={styles.header}>
          <Link to="/products" className={styles.link}>
            <ArrowIcon className={styles.backIcon} /> <Trans>Wróć do zakupów</Trans>
          </Link>

          <div className={styles.checkoutContainer}>
            <CheckoutSteps
              currentStepIndex={0}
              cartId={cartId}
              disabled={(currentCart?.products_count || 0) === 0}
            />
          </div>
        </div>

        <div className={styles.contentWrapper}>
          <div className={styles.content}>
            {paymentsExpiredData?.message && paymentsExpiredData.expired_payments_count > 0 && (
              <Alert type="error" icon={<CreditCard />}>
                <span>{paymentsExpiredData.message}</span>
                <Link to="/dashboard/payments" className={styles.link}>
                  <Trans>Przejdź do zakładki</Trans> <ArrowIcon className={styles.nextIcon} />
                </Link>
              </Alert>
            )}

            <ActionBar cart={currentCart} setSearchKeyword={setSearchQuery} />
            <CartPositions
              cartId={cartId}
              onChange={() => {
                refetchCartsData();
              }}
              searchKeyword={searchQuery}
              noDataPlaceholder={
                <div className={styles.notFound}>
                  <h3>
                    <Trans>Brak wyników</Trans>
                  </h3>
                  {searchQuery && (
                    <span>
                      <Trans>Zmień kryteria wyszukiwania</Trans>
                    </span>
                  )}
                </div>
              }
            />
          </div>
          <div className={styles.sidebar}>
            <CartSummary
              cartId={cartId}
              buttonOnClick={() =>
                (currentCart?.products_count || 0) === 0
                  ? showErrorMessage(t('Koszyk jest pusty'))
                  : navigate(`/checkout/${cartId}`)
              }
              buttonLabel={t('Przejdź do danych adresowych')}
            />
          </div>
        </div>
      </Container>
    </div>
  );
};

export default Cart;
