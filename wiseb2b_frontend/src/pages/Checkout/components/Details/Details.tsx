// sekcja ze szczegółami na podsumowaniu

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import { Truck } from 'react-bootstrap-icons';
import classnames from 'classnames';

import { ICartMainData } from 'api/types';

import styles from 'theme/pages/Checkout/components/Details/Details.module.scss';

// typ danych wejściowych
interface IProps {
  mainData?: ICartMainData;
  returnToCheckout: (boxKey: string) => void;
}

const Details: FC<IProps> = ({ returnToCheckout, mainData }) => {
  return (
    <div
      className={classnames(
        styles.componentWrapper,
        'StylePath-Pages-Checkout-Components-Details'
      )}>
      <div className={styles.row}>
        <div className={styles.column}>
          <div className={styles.title}>
            <Trans>Czas dostawy</Trans>
          </div>
          <div className={styles.date}>
            <Truck />
            <span
              dangerouslySetInnerHTML={{ __html: mainData?.delivery_method?.delivery_time || '' }}
            />
          </div>
        </div>
        <div className={styles.column}>
          <div className={styles.title}>
            <Trans>Sposób dostawy</Trans>
          </div>
          {mainData?.delivery_method?.name}
          <button
            onClick={() => {
              returnToCheckout && returnToCheckout('delivery_method');
            }}>
            <Trans>Edytuj sposób dostawy</Trans>
          </button>
        </div>
      </div>
      <div className={styles.row}>
        <div className={styles.column}>
          <div className={styles.title}>
            <Trans>Symbol zamówienia</Trans>
          </div>
          {mainData?.order_symbol}
        </div>
        <div className={styles.column}>
          <div className={styles.title}>
            <Trans>Metoda płatności</Trans>
          </div>
          {mainData?.payment_method?.name}
          <button
            onClick={() => {
              returnToCheckout && returnToCheckout('payment_method');
            }}>
            <Trans>Edytuj metodę płatności</Trans>
          </button>
        </div>
      </div>
    </div>
  );
};

export default Details;
