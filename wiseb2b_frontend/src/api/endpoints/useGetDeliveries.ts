// Hook odpowiedzialny za pobranie listy dostaw

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface IDeliveryListItem {
  id: number;
  order_id: number;
  symbol: string;
  order_currency: string;
  order_value_net: number;
  order_value_net_formatted: string;
  order_value_gross: number;
  order_value_gross_formatted: string;
  receiver_address: {
    street: string;
    postal_code: string;
    city: string;
  };
  delivery_date: string;
  products_count: number;
}

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest & {
    date_from?: string;
    date_to?: string;
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IDeliveryListItem>;

const getDeliveries = (params?: IRequest): Promise<IResponse> =>
  axios.get('/deliveries', { params });

export const useGetDeliveries = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['deliveries', params], () => getDeliveries(params), options);
