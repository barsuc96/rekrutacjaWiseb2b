/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie pozycji w liście zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  positions: {
    product_id: number;
    quantity: number;
    unit_id: number;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postShoppingListPositions = (shoppingListId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/shopping-lists/${shoppingListId}/positions`, data, {
    env: { FormData: { isForm: true } as any }
  });

export const usePostShoppingListPositions = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postShoppingListPositions(shoppingListId, data), options);
