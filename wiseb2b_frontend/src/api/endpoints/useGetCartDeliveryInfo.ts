// Hook odpowiedzialny za pobranie informacji o dostawie w koszyku

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  cart_id: number;
  info: string;
}

const getCartDeliveryInfo = (id: number): Promise<IResponse> =>
  axios.get(`/carts/${id}/delivery-info`);

export const useGetCartDeliveryInfo = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cart-delivery-info', id], () => getCartDeliveryInfo(id), options);
