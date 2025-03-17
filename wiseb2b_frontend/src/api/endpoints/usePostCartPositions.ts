// Hook odpowiedzialny za tworzenie pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { omit } from 'lodash';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  positions: {
    product_id: number;
    quantity: number;
    unit_id: number;
    cart_assistance?: string;
    promotion_id?: string;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartPositions = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/positions`, data);

export const usePostCartPositions = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation(
    (data: IRequest & { cartId: number }) => postCartPositions(data.cartId, omit(data, 'cartId')),
    options
  );
