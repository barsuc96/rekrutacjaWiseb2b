// Hook odpowiedzialny za pobranie danych potwierdzających złoźenie zamówienia

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  cart_id: number;
  title: string;
  description_html: string;
  payment_info_html: string;
  order_id: number;
  order_number: string;
  button_text: string;
  button_url: string;
  payment_action_enable: boolean;
  payment_action_auto_time: number;
}

const getCartThankYouInfo = (id: number): Promise<IResponse> =>
  axios.get(`/carts/${id}/thank-you-info`);

export const useGetCartThankYouInfo = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cart-thank-you-info', id], () => getCartThankYouInfo(id), options);
