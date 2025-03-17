import React, { useEffect } from 'react';
import { useLocation } from 'react-router-dom';
import qs from 'query-string';
import map from 'lodash/map';

import { useAppNavigate } from 'hooks';
import { usePostOnlinePaymentReturnRedirect } from 'api';

import { Loader } from 'components/controls';

// nazwa pola z tokenem w localStorage
const ONLINEPAYMENT_HASH = 'ONLINEPAYMENT_HASH';

const PaymentSuccess = () => {
  const { search } = useLocation();
  const navigate = useAppNavigate();

  const queryParams = qs.parse(search);

  const hashKey = (queryParams?.hashKey as string) || '';

  const { mutate: confirm } = usePostOnlinePaymentReturnRedirect({
    onSuccess: ({ data: { online_payment_status_page, transaction_hash } }) => {
      if (online_payment_status_page === 'ONLINEPAYMENT_STATUSPAGE') {
        localStorage.setItem(ONLINEPAYMENT_HASH, transaction_hash);
        navigate('/payment/status');
      }
    }
  });

  useEffect(() => {
    confirm({
      transaction_hash: hashKey,
      params: map(queryParams, (param, i) => ({
        key: i as string,
        value: param as string
      }))
    });
  }, []);

  return (
    <>
      <Loader />
    </>
  );
};

export default PaymentSuccess;
