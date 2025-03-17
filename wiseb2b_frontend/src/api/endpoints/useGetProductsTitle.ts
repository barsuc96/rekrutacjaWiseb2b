// Hook odpowiedzialny za pobranie nagłówka listy produktów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IFilterAttributesRequest,
  IPaginationRequest,
  ISearchRequest
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest &
  IFilterAttributesRequest & {
    category_id?: number;
    mode?: 'PROMOTIONS' | 'NEWS' | 'BESTSELLERS';
  };

// typ zwracanych danych
export interface IResponse {
  id: number;
  name: string;
  products_count: number;
}

const getProductsTitle = (params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/title`, { params });

export const useGetProductsTitle = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(['products-title', params], () => getProductsTitle(params), options);
