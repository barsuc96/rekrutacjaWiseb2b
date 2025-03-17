// Hook odpowiedzialny za pobranie listy metod płatności koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICartPaymentsMethodListItem {
  cash_on_delivery_editable: false;
  commission: number;
  id: number;
  image: string;
  limit_comment: null;
  name: string;
  price_formatted_to_show: string;
  price_gross: number;
  price_gross_formatted: string;
  price_net: number;
  price_net_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICartPaymentsMethodListItem>;

const getCartPaymentsMethods = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/carts/${id}/payment-methods`, { params });

export const useGetCartPaymentsMethods = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-payment-methods', id, params],
    () => getCartPaymentsMethods(id, params),
    options
  );
