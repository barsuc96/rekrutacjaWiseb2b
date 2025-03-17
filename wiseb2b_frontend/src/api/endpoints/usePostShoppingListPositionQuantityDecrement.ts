// Hook odpowiedzialny za zmniejszenie ilości danej pozycji na liście zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postShoppingListPositionQuantityDecrement = (
  shoppingListId: number,
  positionId: number
): Promise<IResponse> =>
  axios.post(`/shopping-lists/${shoppingListId}/positions/${positionId}/quantity-decrement`, {});

export const usePostShoppingListPositionQuantityDecrement = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError, { positionId: number }>
) =>
  useMutation(
    (data: { positionId: number }) =>
      postShoppingListPositionQuantityDecrement(shoppingListId, data.positionId),
    options
  );
