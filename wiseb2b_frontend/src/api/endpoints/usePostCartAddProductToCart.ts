// Hook odpowiedzialny za tworzenie pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  cart_id: number;
  products: {
    product_id: number;
    quantity: number;
    unit_id: number;
    warehouse_id?: number;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartAddProductToCart = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/add-product-to-cart`, data);

export const usePostCartAddProductToCart = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postCartAddProductToCart(data.cart_id, data), options);
