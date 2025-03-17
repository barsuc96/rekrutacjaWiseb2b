// Hook odpowiedzialny za pobranie podsumowania listy dostaw na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  done: {
    count: number;
  };
  inway: {
    count: number;
  };
  topay: {
    count: number;
  };
  total: {
    value: number;
    value_formatted: string;
    currency: string;
  };
}

const getDashboardDeliveriesSummary = (): Promise<IResponse> =>
  axios.get('dashboards/deliveries/summary');

export const useGetDashboardDeliveriesSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(
    ['dashboard-deliveries-summary'],
    getDashboardDeliveriesSummary,
    options
  );
