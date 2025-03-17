// Hook odpowiedzialny za pobranie podsumowania listy platnosci na dashboardzie

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

const getDashboardSettlementsSummary = (): Promise<IResponse> =>
  axios.get('dashboards/settlements/summary');

export const useGetDashboardSettlementsSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['dashboard-settlements-summary'], getDashboardSettlementsSummary, options);
