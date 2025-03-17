// Hook odpowiedzialny za pobranie listy handlowców

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ITraderListItem {
  id: number;
  first_name: string;
  last_name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ITraderListItem>;

const getUsersTraders = (params?: IRequest): Promise<IResponse> =>
  axios.get('/users/traders', { params });

export const useGetUsersTraders = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['users-traders', params], () => getUsersTraders(params), options);
