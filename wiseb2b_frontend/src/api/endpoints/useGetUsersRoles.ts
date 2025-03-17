// Hook odpowiedzialny za pobranie listy handlowców

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IUserRoles
} from 'api/types';

export interface IRoleListItem {
  role: IUserRoles;
  role_name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IRoleListItem>;

const getUsersRoles = (params?: IRequest): Promise<IResponse> =>
  axios.get('/users/roles', { params });

export const useGetUsersRoles = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['users-roles', params], () => getUsersRoles(params), options);
