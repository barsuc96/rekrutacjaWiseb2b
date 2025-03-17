// Hook odpowiedzialny za pobranie listy krajów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICountryCodeItem {
  code: number;
  language: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICountryCodeItem>;

const getCountryCodes = (params?: IRequest): Promise<IResponse> =>
  axios.get('/country-codes', { params });

export const useGetCountryCodes = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['country-codes', params], () => getCountryCodes(params), options);
