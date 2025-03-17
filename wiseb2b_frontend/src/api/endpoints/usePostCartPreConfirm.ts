// Hook odpowiedzialny za walidację koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartPreConfirm = (cartId: number): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/pre-confirm`, {});

export const usePostCartPreConfirm = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => postCartPreConfirm(cartId), options);
