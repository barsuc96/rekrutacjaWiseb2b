// Hook odpowiedzialny za pobranie listy kategorii

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICategoryListItem,
  ICommandResponseError as IError,
  IFilterAttributesRequest,
  IPaginationRequest,
  IPaginationResponse,
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
type IResponse = IPaginationResponse<ICategoryListItem>;

const getProductsCategories = (params?: IRequest): Promise<IResponse> =>
  axios.get('/products/categories', { params });

export const useGetProductsCategories = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-categories', params],
    () => getProductsCategories(params),
    options
  );
