// Hook odpowiedzialny za pobranie szczegółów klienta

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  id: number;
  name: string;
  first_name: string;
  last_name: string;
  email: string;
  status: number;
  phone: string;
  offers_count: number;
  orders_count: number;
  address: {
    street: string;
    building: string;
    apartment: string;
    postal_code: string;
    city: string;
    state: null | string;
    country: null | string;
    country_code: string;
  };
  status_symbol: string;
  status_formatted: string;
  store_id: 1;
}

const getClient = (id: number): Promise<IResponse> => axios.get(`/clients/${id}`);

export const useGetClient = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['client', id], () => getClient(id), options);
