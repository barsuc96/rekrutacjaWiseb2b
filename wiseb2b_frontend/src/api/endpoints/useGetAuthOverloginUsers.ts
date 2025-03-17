// Hook odpowiedzialny za pobranie listy użytkowników możliwych do przelogowania

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IOverloginUser {
  id: number;
  name: string;
  login: string;
}

export interface IOverloginUserListItem {
  id: number;
  name: string;
  users_list: IOverloginUser[];
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IOverloginUserListItem>;

const getAuthOverloginUsers = (params?: IRequest): Promise<IResponse> =>
  axios.get('/auth/overlogin-users', { params });

export const useGetAuthOverloginUsers = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['auth-overlogin-users', params],
    () => getAuthOverloginUsers(params),
    options
  );
