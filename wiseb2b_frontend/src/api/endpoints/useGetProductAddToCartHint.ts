// Hook odpowiedzialny za pobranie informacji przy dodawaniu produktu do koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
interface IRequest {
  unit_id: number;
  quantity: number;
}

// typ zwracanych danych
interface IResponse {
  id: number;
  hint?: string;
  color?: string;
}

const getProductAddToCartHint = (productId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/${productId}/add-to-cart-hint`, { params });

export const useGetProductAddToCartHint = (
  productId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['product-add-to-cart-hint', productId, params],
    () => getProductAddToCartHint(productId, params),
    options
  );
