// szczegóły płatności

import React, { FC } from 'react';
import { Trans } from 'react-i18next';
import classnames from 'classnames';

import { useGetSettlement } from 'api';

import styles from 'theme/pages/Payments/components/Details/Details.module.scss';

// typ danych wejściowych
interface IProps {
  paymentId: number;
}

const Details: FC<IProps> = ({ paymentId }) => {
  // pobranie szczegółów płatności
  const { data: paymentData } = useGetSettlement(paymentId);

  return (
    <div className={classnames(styles.wrapperComponent, 'StylePath-Pages-components-Details')}>
      <div>
        <div className={styles.title}>
          <Trans>Szczegóły płatności</Trans>
        </div>
        <span className={styles.label}>
          <Trans>Nazwa banku</Trans>:
        </span>{' '}
        {paymentData?.bank_name}
        <br />
        <span className={styles.label}>
          <Trans>Metoda płatności</Trans>:
        </span>{' '}
        {paymentData?.payment_name}
        <br />
        <span className={styles.label}>
          <Trans>Numer konta</Trans>:
        </span>{' '}
        {paymentData?.account}
      </div>
      <div>
        <div className={styles.title}>
          <Trans>Do zapłaty</Trans>
        </div>
        <div className={styles.price}>
          {paymentData?.amount_formatted} {paymentData?.currency}
        </div>
      </div>
      <div>
        <div className={styles.title}>
          <Trans>Dane płatnika</Trans>
        </div>
        {paymentData?.payer_name}
        <br />
        {paymentData?.payer_address}
        <br />
        {paymentData?.payer_postal_code}, {paymentData?.payer_city}{' '}
        {paymentData?.payer_country_code}
      </div>
      <div>
        <div className={styles.title}>
          <Trans>Dokument finansowy</Trans>
        </div>
        <Trans>Brak dokumentów</Trans>
      </div>
    </div>
  );
};

export default Details;
