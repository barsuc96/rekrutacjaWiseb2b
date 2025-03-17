// Hook odpowiedzialny za pobranie informacji o przeterminowanych płatnościach

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  expired_payments_count: number;
  message: string;
}

const getCartPaymentsExpired = (cartId: number): Promise<IResponse> =>
  axios.get(`carts/${cartId}/payments-expired`);

export const useGetCartPaymentsExpired = (
  cartId: number,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-payments-expired', cartId],
    () => getCartPaymentsExpired(cartId),
    options
  );
