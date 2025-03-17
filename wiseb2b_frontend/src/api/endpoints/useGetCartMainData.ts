// Hook odpowiedzialny za pobranie main data koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IReceiverPoint } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  id: number;
  cart_id: number;
  comment?: string;
  customer?: {
    name: string;
    contact_name: string;
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
  delivery_address: {
    id: number;
    name: string;
    first_name?: string;
    last_name?: string;
    email?: string;
    phone?: string;
    address: {
      street: string;
      postal_code: string;
      city: string;
    };
  };
  delivery_method?: {
    id: number;
    parcel_point_widget: 'inpost' | 'pocztex' | 'dpd_pickup';
    delivery_time: string;
    free_delivery_from: number;
    free_delivery_from_formatted: string;
    free_delivery_from_message: string;
    image: string;
    name: string;
    price_gross: number;
    price_gross_formatted: string;
    price_net: number;
    price_net_formatted: string;
  };
  receiver_delivery_point?: IReceiverPoint;
  receiver_delivery_point_summary?: IReceiverPoint;
  e_invoice: boolean;
  e_invoice_email: null;
  payment_method?: {
    id: number;
    name: string;
    icon: string;
  };
  receiver?: {
    id: number;
    name: string;
    first_name?: string;
    last_name?: string;
    email?: string;
    phone?: string;
    contact_name: string;
    address: {
      street: string;
      postal_code: string;
      city: string;
      building: string;
      apartment: string;
      country: string;
    };
  };
  delivery_date?: string;
  order_symbol?: string;
}

const getCartMainData = (id: number): Promise<IResponse> => axios.get(`/carts/${id}/maindata`);

export const useGetCartMainData = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cart-main-data', id], () => getCartMainData(id), options);
