// Hook odpowiedzialny za pobranie listy produktw z typu subelementu produktu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IProductListItem
} from 'api/types';

// parametry requestu do api
type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getProductSubelementItems = (
  productId: number,
  subelementId: number,
  params?: IRequest
): Promise<IResponse> =>
  axios.get(`/products/${productId}/subelements/${subelementId}`, { params });

export const useGetProductSubelementItems = (
  productId: number,
  subelementId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['product-subelement-items', productId, subelementId],
    () => getProductSubelementItems(productId, subelementId, params),
    options
  );
