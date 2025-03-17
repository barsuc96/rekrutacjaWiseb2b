// Hook odpowiedzialny za pobranie listy produktóe "inni kupili"

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

const getProductsBoughtByOthers = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/${id}/products-bought-by-others`, { params });

export const useGetProductsBoughtByOthers = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-bought-by-others', id, params],
    () => getProductsBoughtByOthers(id, params),
    options
  );
