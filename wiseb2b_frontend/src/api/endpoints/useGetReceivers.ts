// Hook odpowiedzialny za pobranie listy odbiorców

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface IReceiverListItem {
  id: number;
  lp: number;
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
type IResponse = IPaginationResponse<IReceiverListItem>;

const getReceivers = (params?: IRequest): Promise<IResponse> => axios.get('receivers', { params });

export const useGetReceivers = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['receivers', params], () => getReceivers(params), options);
