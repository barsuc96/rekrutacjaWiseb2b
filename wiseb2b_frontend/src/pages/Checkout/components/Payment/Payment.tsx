// sekcja z metodami płatności

import React, { FC } from 'react';
import classnames from 'classnames';

import { useGetCartPaymentsMethods } from 'api';
import { ICartMainData, ICartMainDataUpdateRequest } from 'api/types';
import { Radio } from 'components/controls';

import styles from 'theme/pages/Checkout/components/Payment/Payment.module.scss';

// typ danych wejściowych
interface IProps {
  cartId: number;
  paymentMethod: ICartMainData['payment_method'];
  updateCartMainData: (data: Partial<ICartMainDataUpdateRequest>) => void;
}

const Payment: FC<IProps> = ({ cartId, paymentMethod, updateCartMainData }) => {
  // pobranie listy metod płatności
  const { data: cartPaymentsMethodsData } = useGetCartPaymentsMethods(cartId, {
    page: 1,
    limit: 999
  });

  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-Payment'
      )}>
      {cartPaymentsMethodsData?.items.map((item) => (
        <label
          key={item.id}
          className={classnames(styles.item, { [styles.active]: item.id === paymentMethod?.id })}>
          <Radio
            checked={item.id === paymentMethod?.id}
            onClick={() => updateCartMainData({ payment_method_id: item.id })}
          />

          <div className={styles.content}>
            <div className={styles.iconWrapper}>
              <img className={styles.icon} src={item.image} alt="icon" />
            </div>
            <div className={styles.name}>{item.name}</div>
          </div>
          <div>{item.price_formatted_to_show}</div>
        </label>
      ))}
    </div>
  );
};

export default Payment;
