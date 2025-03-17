// Hook odpowiedzialny za pobranie koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  cart_id: number;
  cart_price_gross?: number;
  cart_price_gross_formatted?: string;
  cart_price_net?: number;
  cart_price_net_formatted?: string;
  delivery_price?: number;
  delivery_price_formatted?: string;
  payment_method?: string;
  currency: string;
  total_price_gross?: number;
  total_price_gross_formatted?: string;
  total_price_net?: number;
  total_price_net_formatted?: string;
}

const getCart = (id: number): Promise<IResponse> => axios.get(`/carts/${id}`);

export const useGetCart = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cart', id], () => getCart(id), options);
