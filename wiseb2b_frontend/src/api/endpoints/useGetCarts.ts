// Hook odpowiedzialny za pobranie listy koszyków

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICartListItem {
  id: number;
  name: string;
  currency: string;
  value_gross: number;
  value_gross_formatted: string;
  value_net: number;
  value_net_formatted: string;
  products_count: number;
  create_datetime: string;
  positions_value_net_formatted: string;
  positions_value_gross_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICartListItem>;

const getCarts = (params?: IRequest): Promise<IResponse> => axios.get('/carts', { params });

export const useGetCarts = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['carts', params], () => getCarts(params), options);

export const useGetCartsAll = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>('carts-all', () => getCarts({ page: 1, limit: 999 }), options);
