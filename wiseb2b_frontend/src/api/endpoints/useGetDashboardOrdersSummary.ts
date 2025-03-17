// Hook odpowiedzialny za pobranie podsumowania listy zamówień na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  paid: {
    count: number;
  };
  expired: {
    count: number;
  };
  upcoming: {
    count: number;
  };
  total: {
    value: number;
    value_formatted: string;
    currency: string;
  };
}

const getDashboardOrdersSummary = (): Promise<IResponse> => axios.get('dashboards/orders/summary');

export const useGetDashboardOrdersSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['dashboard-orders-summary'], getDashboardOrdersSummary, options);
