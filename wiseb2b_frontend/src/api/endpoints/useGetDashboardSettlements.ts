// Hook odpowiedzialny za pobranie listy płatności na dashboardzie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IDashboardSettlementsListItem {
  currency: string;
  date: string;
  payment_diff_days: number;
  symbol: string;
  value: number;
  value_formatted: string;
}

// typ zwracanych danych
export interface IResponse {
  all: IDashboardSettlementsListItem[];
  expired: IDashboardSettlementsListItem[];
  upcoming: IDashboardSettlementsListItem[];
}

const getDashboardSettlements = (): Promise<IResponse> => axios.get('dashboards/settlements');

export const useGetDashboardSettlements = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['dashboard-settlements'], getDashboardSettlements, options);
