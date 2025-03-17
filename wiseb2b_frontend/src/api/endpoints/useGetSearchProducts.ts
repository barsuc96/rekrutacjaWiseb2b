// Hook odpowiedzialny za pobranie listy produktów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IFilterAttributesRequest,
  IProductListItem
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest &
  IFilterAttributesRequest & {
    category_id?: number;
    sort_method?: string;
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getSearchProducts = (params?: IRequest): Promise<IResponse> =>
  axios.get('/search/products', { params });

export const useGetSearchProducts = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['search-products', params],
    () => getSearchProducts(params),
    options
  );
