// Hook odpowiedzialny za pobranie listy faktur na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IDashboardInvoiceListItem {
  currency: string;
  id: number;
  issued_datetime: string;
  payment_datetime: string;
  symbol: string;
  value: number;
  value_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IDashboardInvoiceListItem>;

const getDashboardInvoices = (params?: IRequest): Promise<IResponse> =>
  axios.get('dashboards/invoices', { params });

export const useGetDashboardInvoices = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['dashboard-invoices', params],
    () => getDashboardInvoices(params),
    options
  );
