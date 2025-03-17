// Hook odpowiedzialny za pobranie listy typów subelementów w produkcie

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IProductSubelementType {
  id: number;
  name: string;
}

// parametry requestu do api
type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductSubelementType>;

const getProductSubelementsTypes = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/${id}/subelements-types`, { params });

export const useGetProductSubelementsTypes = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['product-subelements-types', id, params],
    () => getProductSubelementsTypes(id, params),
    options
  );
