// Hook odpowiedzialny za aktualozację main data koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

export interface IReceiverPoint {
  type: string;
  symbol: string;
  address: {
    street: string;
    building: string;
    apartment: string;
    postal_code: string;
    city: string;
    state: string;
    country?: string;
    country_code?: string;
  };
}

// parametry requestu do api
export interface IRequest {
  client?: {
    name: string;
    address: {
      street: string;
      postal_code: string;
      city: string;
      building: string;
      apartment: string;
      country: string;
      country_code: string;
    };
    nip: string;
    email: string;
    phone?: string;
  };
  receiver_id?: number;
  delivery_address_id?: number;
  delivery_method_id?: number;
  payment_method_id?: number;
  comment?: string;
  e_invoice?: boolean;
  e_invoice_email?: string;
  receiver_delivery_point?: IReceiverPoint;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCartMainData = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/carts/${cartId}/maindata`, data);

export const usePutCartMainData = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCartMainData(cartId, data), options);
