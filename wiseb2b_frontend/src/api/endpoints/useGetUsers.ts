// Hook odpowiedzialny za pobranie listy użytkowników

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IUserRoles
} from 'api/types';

export interface IUserListItem {
  id: number;
  first_name: string;
  last_name: string;
  total_offers: number;
  total_orders: number;
  status: number;
  email: string;
  can_modify_user: boolean;
  role: IUserRoles;
}

// parametry requestu do api
export type IRequest = IPaginationRequest & ISearchRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IUserListItem>;

const getUsers = (params?: IRequest): Promise<IResponse> => axios.get('/users', { params });

export const useGetUsers = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['users', params], () => getUsers(params), options);
