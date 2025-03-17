// Hook odpowiedzialny za skasowanie pozycji z listy zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteShoppingListPosition = (
  shoppingListId: number,
  positionId: number
): Promise<IResponse> => axios.delete(`/shopping-lists/${shoppingListId}/position/${positionId}`);

export const useDeleteShoppingListPosition = (
  shoppingListId: number,
  positionId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteShoppingListPosition(shoppingListId, positionId), options);
