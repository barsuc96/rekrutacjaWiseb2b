// Hook odpowiedzialny za zwiększenie ilości danej pozycji na liście zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postShoppingListPositionQuantityIncrement = (
  shoppingListId: number,
  positionId: number
): Promise<IResponse> =>
  axios.post(`/shopping-lists/${shoppingListId}/positions/${positionId}/quantity-increment`, {});

export const usePostShoppingListPositionQuantityIncrement = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError, { positionId: number }>
) =>
  useMutation(
    (data: { positionId: number }) =>
      postShoppingListPositionQuantityIncrement(shoppingListId, data.positionId),
    options
  );
