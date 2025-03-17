// Hook odpowiedzialny za pobranie listy klientów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface IClientListItem {
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
  offers_count: number;
  orders_count: number;
  status_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest & ISearchRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IClientListItem>;

const getClients = (params?: IRequest): Promise<IResponse> => axios.get('/clients', { params });

export const useGetClients = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['clients', params], () => getClients(params), options);
