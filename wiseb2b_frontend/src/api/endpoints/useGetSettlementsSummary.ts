// Hook odpowiedzialny za pobranie podsumowania płatności

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

interface ISettlementsSummaryItem {
  payments: {
    count: number;
  };
  invoices: {
    count: number;
  };
  total: {
    value: number;
    value_formatted: string;
  };
  currency: string;
}

// typ zwracanego obiektu
export type IResponse = Record<'paid' | 'expired' | 'upcoming', ISettlementsSummaryItem>;

const getSettlementsSummary = (): Promise<IResponse> => axios.get('/settlements/summary');

export const useGetSettlementsSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['payments-summary'], () => getSettlementsSummary(), options);
