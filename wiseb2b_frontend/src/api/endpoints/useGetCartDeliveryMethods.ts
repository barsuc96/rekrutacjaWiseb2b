// Hook odpowiedzialny za pobranie listy metod dostawy koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICartDeliveryMethodListItem {
  id: number;
  provider: string;
  delivery_time: string;
  delivery_info: string;
  free_delivery_from: string;
  free_delivery_from_message: string;
  image: string;
  name: string;
  price_gross: number;
  price_gross_formatted: string;
  price_net: number;
  price_net_formatted: string;
  currency: string;
  style_type: string;
  parcel_point_widget: 'inpost' | 'pocztex' | 'dpd_pickup';
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICartDeliveryMethodListItem>;

const getCartDeliveryMethods = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/carts/${id}/delivery-methods`, { params });

export const useGetCartDeliveryMethods = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-delivery-methods', id, params],
    () => getCartDeliveryMethods(id, params),
    options
  );
