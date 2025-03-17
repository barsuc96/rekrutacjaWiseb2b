// Hook odpowiedzialny za pobranie listy ostatnio oglądanych produktów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IProductListItem
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getProductsLastViewed = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/${id}/products-last-visited`, { params });

export const useGetProductsLastViewed = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-last-visited', id, params],
    () => getProductsLastViewed(id, params),
    options
  );
