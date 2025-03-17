// Hook odpowiedzialny za pobranie listy krajów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IUsersCountryListItem {
  code: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IUsersCountryListItem>;

const getUsersCountries = (params?: IRequest): Promise<IResponse> =>
  axios.get('/users/countries', { params });

export const useGetUsersCountries = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['users-countries', params],
    () => getUsersCountries(params),
    options
  );
