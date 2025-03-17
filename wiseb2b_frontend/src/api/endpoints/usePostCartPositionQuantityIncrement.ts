// Hook odpowiedzialny za zwiększenie ilości danej pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCartPositionQuantityIncrement = (
  cartId: number,
  positionId: number
): Promise<IResponse> =>
  axios.post(`/carts/${cartId}/positions/${positionId}/quantity-increment`, {});

export const usePostCartPositionQuantityIncrement = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, { positionId: number }>
) =>
  useMutation(
    (data: { positionId: number }) => postCartPositionQuantityIncrement(cartId, data.positionId),
    options
  );
