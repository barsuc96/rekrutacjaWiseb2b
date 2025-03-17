// Hook odpowiedzialny za pobranie listy produktow

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
    filter_attributes?: string;
    mode?: 'PROMOTIONS' | 'NEWS' | 'BESTSELLERS';
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getProducts = (params?: IRequest): Promise<IResponse> => axios.get('/products', { params });

export const useGetProducts = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['products', params], () => getProducts(params), options);
