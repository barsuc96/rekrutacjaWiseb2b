// podsumowanie koszyka - box w sidebar

import React, { FC, PropsWithChildren } from 'react';
import { Trans } from 'react-i18next';
import { ArrowRight } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { useGetCart } from 'api';
import { useAppNavigate } from 'hooks';
import { Button } from 'components/controls';

import styles from 'theme/components/containers/CartSummary/CartSummary.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
  buttonOnClick: () => void;
  buttonLabel: string;
  isLoading?: boolean;
}

const CartSummary: FC<IProps> = ({ cartId, buttonOnClick, buttonLabel, isLoading }) => {
  const navigate = useAppNavigate();

  // pobranie szczegółów koszyka
  const { data: cartData } = useGetCart(cartId, {
    onError: () => {
      navigate('/');
    }
  });

  const Row: FC<PropsWithChildren> = ({ children }) => (
    <div className={styles.row}> {children}</div>
  );

  return (
    <div
      className={classnames(
        styles.wrapperComponent,
        'StylePath-Components-Containers-CartSummary'
      )}>
      <div>
        <Row>
          <div className={styles.labelBold}>
            <Trans>Wartość koszyka</Trans>
          </div>
          <div className={styles.valueCartNet}>
            {cartData?.cart_price_net_formatted ? (
              <>
                <span>
                  {cartData.cart_price_net_formatted} {cartData.currency}
                </span>{' '}
                <Trans>netto</Trans>
              </>
            ) : (
              <span>-</span>
            )}
          </div>
        </Row>
        <Row>
          <div />
          <div className={styles.valueCartGross}>
            {cartData?.cart_price_gross_formatted ? (
              <>
                <span>
                  {cartData.cart_price_gross_formatted} {cartData.currency}
                </span>{' '}
                <Trans>brutto</Trans>
              </>
            ) : (
              <span>-</span>
            )}
          </div>
        </Row>
      </div>
      <div className={styles.shipmentSection}>
        <Row>
          <div>
            <Trans>Dostawa</Trans>
          </div>
          <div>
            {cartData?.delivery_price_formatted
              ? `${cartData.delivery_price_formatted} ${cartData.currency}`
              : '-'}
          </div>
        </Row>
        <Row>
          <div>
            <Trans>Płatność</Trans>
          </div>
          <div>{cartData?.payment_method || '-'}</div>
        </Row>
      </div>
      <div>
        <Row>
          <div className={styles.labelBold}>
            <Trans>Łączna kwota</Trans>
          </div>
          <div className={styles.valueTotalNet}>
            {cartData?.total_price_net_formatted ? (
              <>
                <span>
                  {cartData.total_price_net_formatted} {cartData.currency}
                </span>{' '}
                <Trans>netto</Trans>
              </>
            ) : (
              <span>-</span>
            )}
          </div>
        </Row>
        <Row>
          <div />
          <div className={styles.valueTotalGross}>
            {cartData?.total_price_gross_formatted ? (
              <>
                <span>
                  {cartData.total_price_gross_formatted} {cartData.currency}
                </span>{' '}
                <Trans>brutto</Trans>
              </>
            ) : (
              <span>-</span>
            )}
          </div>
        </Row>
      </div>

      <div className={styles.buttonWrapper}>
        <Button onClick={() => buttonOnClick()} loading={isLoading}>
          {buttonLabel} <ArrowRight className={styles.buttonArrow} />
        </Button>
      </div>
    </div>
  );
};

export default CartSummary;
