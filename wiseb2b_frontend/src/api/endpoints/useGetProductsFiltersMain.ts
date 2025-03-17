// Hook odpowiedzialny za pobranie listy filtrów głównych

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IFilterAttributesRequest,
  IProductsFilter
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest &
  IFilterAttributesRequest & {
    category_id?: number;
    mode?: 'PROMOTIONS' | 'NEWS' | 'BESTSELLERS';
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductsFilter>;

const getProductsFiltersMain = (params?: IRequest): Promise<IResponse> =>
  axios.get('/products/filters-main', { params });

export const useGetProductsFiltersMain = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-filters-main', params],
    () => getProductsFiltersMain(params),
    options
  );
