// Hook odpowiedzialny za pobranie listy zamówień

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IStatus
} from 'api/types';

export interface IOrderListItem {
  id: number;
  lp: number;
  user_id: number;
  user_name: string;
  status: IStatus;
  status_message: string;
  create_date: string;
  products_total_count: number;
  value_net: number;
  value_net_formatted: string;
  value_gross: number;
  value_gross_formatted: string;
  currency: string;
  completion_date: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest & {
    order_date_from?: string;
    order_date_to?: string;
    user_ordering?: number;
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IOrderListItem>;

const getOrders = (params?: IRequest): Promise<IResponse> => axios.get('/orders', { params });

export const useGetOrders = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['orders', params], () => getOrders(params), options);
