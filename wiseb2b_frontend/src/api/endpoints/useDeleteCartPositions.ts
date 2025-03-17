// Hook odpowiedzialny za skasowanie pozycji koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

type IPosition = { id: number };

// parametry requestu do api
type IRequest = {
  cart_id: number;
  positions: IPosition[];
};

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCartPositions = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.delete(`/carts/${cartId}/positions`, { data });

export const useDeleteCartPositions = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => deleteCartPositions(cartId, data), options);
