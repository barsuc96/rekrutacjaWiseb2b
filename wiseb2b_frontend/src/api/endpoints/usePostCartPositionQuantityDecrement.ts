// Hook odpowiedzialny za zmniejszenie ilości danej pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartPositionQuantityDecrement = (
  cartId: number,
  positionId: number
): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/positions/${positionId}/quantity-decrement`, {});

export const usePostCartPositionQuantityDecrement = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, { positionId: number }>
) =>
  useMutation(
    (data: { positionId: number }) => postCartPositionQuantityDecrement(cartId, data.positionId),
    options
  );
