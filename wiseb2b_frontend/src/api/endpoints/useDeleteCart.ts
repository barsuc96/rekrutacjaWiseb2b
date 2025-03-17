// Hook odpowiedzialny za skasowanie koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCart = (cartId: number): Promise<IResponse> => axios.delete(`/carts/${cartId}`);

export const useDeleteCart = (cartId: number, options?: UseMutationOptions<IResponse, IError>) =>
  useMutation(() => deleteCart(cartId), options);
