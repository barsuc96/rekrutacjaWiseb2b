// Hook odpowiedzialny za aktualizację koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  name: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCart = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/carts/${cartId}`, data);

export const usePutCart = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCart(cartId, data), options);
