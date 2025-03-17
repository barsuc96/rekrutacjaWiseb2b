// Hook odpowiedzialny za pobranie listy zamówień na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IStatus
} from 'api/types';

export interface IDashboardOrderListItem {
  currency: string;
  id: number;
  date_order: string;
  status: IStatus;
  status_message: string;
  status_type: number;
  symbol: string;
  value: number;
  value_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IDashboardOrderListItem>;

const getDashboardOrders = (params?: IRequest): Promise<IResponse> =>
  axios.get('dashboards/orders', { params });

export const useGetDashboardOrders = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['dashboard-orders', params],
    () => getDashboardOrders(params),
    options
  );
