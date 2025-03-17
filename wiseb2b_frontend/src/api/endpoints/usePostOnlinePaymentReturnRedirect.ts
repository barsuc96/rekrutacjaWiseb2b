// Hook odpowiedzialny za inicjowanie płatności online

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  transaction_hash: string;
  params: {
    key: string;
    value: string;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess & {
  data: {
    online_payment_status_page: 'ONLINEPAYMENT_STATUSPAGE';
    transaction_hash: string;
  };
};

const postOnlinePaymentReturnRedirect = (data: IRequest): Promise<IResponse> =>
  axios.post('/onlinepayment/return_redirect', data);

export const usePostOnlinePaymentReturnRedirect = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postOnlinePaymentReturnRedirect(data), options);
