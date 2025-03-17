// Hook odpowiedzialny za pobranie listy krajów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IClientCountryListItem {
  code: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IClientCountryListItem>;

const getClientCountries = (params?: IRequest): Promise<IResponse> =>
  axios.get('/clients/countries', { params });

export const useGetClientCountries = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['clients-countries', params],
    () => getClientCountries(params),
    options
  );
