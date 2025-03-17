// Hook odpowiedzialny za skasowanie listy zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteShoppingList = (shoppingListId: number): Promise<IResponse> =>
  axios.delete(`/shopping-lists/${shoppingListId}`);

export const useDeleteShoppingList = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteShoppingList(shoppingListId), options);
