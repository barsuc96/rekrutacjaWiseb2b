// Hook odpowiedzialny za pobranie listy krajów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IReceiversCountryListItem {
  code: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IReceiversCountryListItem>;

const getReceiversCountries = (params?: IRequest): Promise<IResponse> =>
  axios.get('/receivers/countries', { params });

export const useGetReceiversCountries = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['receivers-countries', params],
    () => getReceiversCountries(params),
    options
  );
