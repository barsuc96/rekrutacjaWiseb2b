// Hook odpowiedzialny za pobranie danych do podsumowaniu dashboardu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  expired_payments: {
    count: number;
  };
  suspended_deliveries: {
    count: number;
  };
  orders: {
    count: number;
  };
  sales: {
    value: number;
    value_formatted: string;
    currency: string;
  };
}

const getDashboardSummary = (): Promise<IResponse> => axios.get('dashboards/summary');

export const useGetDashboardSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['dashboard-summary'], getDashboardSummary, options);
