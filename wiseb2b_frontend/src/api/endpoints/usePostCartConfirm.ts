// Hook odpowiedzialny za potwiedzene koszyka (stworzenie zamówienia)

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartConfirm = (cartId: number): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/confirm`, {});

export const usePostCartConfirm = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => postCartConfirm(cartId), options);
