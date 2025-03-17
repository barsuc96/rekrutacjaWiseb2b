// Hook odpowiedzialny za pobranie listy dostaw na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IStatus
} from 'api/types';

export interface IDashboardDeliveryListItem {
  currency: string;
  id: number;
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
type IResponse = IPaginationResponse<IDashboardDeliveryListItem>;

const getDashboardDeliveries = (params?: IRequest): Promise<IResponse> =>
  axios.get('dashboards/deliveries', { params });

export const useGetDashboardDeliveries = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['dashboard-deliveries', params],
    () => getDashboardDeliveries(params),
    options
  );
