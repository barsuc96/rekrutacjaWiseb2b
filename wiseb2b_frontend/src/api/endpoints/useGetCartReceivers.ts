// Hook odpowiedzialny za pobranie listy odbiorców w koszyku

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface ICartReceiverListItem {
  id: number;
  name: string;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  address: {
    street: string;
    postal_code: string;
    city: string;
  };
}

// parametry requestu do api
export type IRequest = IPaginationRequest & ISearchRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICartReceiverListItem>;

const getCartReceivers = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/carts/${id}/receivers`, { params });

export const useGetCartReceivers = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-receivers', id, params],
    () => getCartReceivers(id, params),
    options
  );
