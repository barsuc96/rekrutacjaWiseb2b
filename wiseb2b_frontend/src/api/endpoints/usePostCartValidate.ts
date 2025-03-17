// Hook odpowiedzialny za walidację koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

export interface IRequest {
  verification_scope?: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartValidate = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/validate`, data);

export const usePostCartValidate = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postCartValidate(cartId, data), options);
