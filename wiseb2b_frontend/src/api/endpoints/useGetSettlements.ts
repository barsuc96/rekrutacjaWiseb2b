// Hook odpowiedzialny za pobranie listy płatności

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface ISettlementsListItem {
  id: number;
  payment_date: string;
  symbol: string;
  status: number;
  amount: number;
  amount_formatted: string;
  currency: string;
  payment_diff_days: number;
}

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest & {
    date_from?: string;
    date_to?: string;
    status_type?: 'PAID' | 'CURRENT' | 'EXPIRED';
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<ISettlementsListItem>;

const getSettlements = (params?: IRequest): Promise<IResponse> =>
  axios.get('/settlements', { params });

export const useGetSettlements = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['payments', params], () => getSettlements(params), options);
