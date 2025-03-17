// Hook odpowiedzialny za pobranie szczgółów płatności

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  symbol: string;
  payer_name: string;
  payer_address: string;
  payer_postal_code: string;
  payer_city: string;
  payer_country_code: string;
  payment_name: string;
  amount: number;
  currency: string;
  bank_name: string;
  account: string;
  status: number;
  payment_date: string;
  payment_diff_days: number;
  status_text: string;
  amount_formatted: string;
}

const getSettlement = (id: number): Promise<IResponse> => axios.get(`/settlements/${id}`);

export const useGetSettlement = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['payment', id], () => getSettlement(id), options);
