// Hook odpowiedzialny za pobranie listy moźliwych sortowań listy produktu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IProductsSortMethod {
  id: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductsSortMethod>;

const getProductsSortMethods = (params?: IRequest): Promise<IResponse> =>
  axios.get('/products/sort-methods', { params });

export const useGetProductsSortMethods = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-sort-methods', params],
    () => getProductsSortMethods(params),
    options
  );
