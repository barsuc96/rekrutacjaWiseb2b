import React from 'react';

import { useGetOnlinePaymentStatus } from 'api';

import { Container } from 'components/controls';

// nazwa pola z tokenem w localStorage
const ONLINEPAYMENT_HASH = 'ONLINEPAYMENT_HASH';

const PaymentStatus = () => {
  const hash = localStorage.getItem(ONLINEPAYMENT_HASH);

  const { data: onlinePaymentStatus } = useGetOnlinePaymentStatus(hash || '');

  return (
    <Container>
      <h1>{onlinePaymentStatus?.payment_status_label}</h1>
      <div dangerouslySetInnerHTML={{ __html: onlinePaymentStatus?.description_html || '' }}></div>
    </Container>
  );
};

export default PaymentStatus;
