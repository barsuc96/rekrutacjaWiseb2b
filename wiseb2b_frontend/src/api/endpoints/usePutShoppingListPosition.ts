// Hook odpowiedzialny za aktualozację pozycji listy zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { omit } from 'lodash';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  quantity: number;
  unit_id: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putShoppingListPosition = (
  shoppingListId: number,
  positionId: number,
  data: IRequest
): Promise<IResponse> =>
  axios.put(`/shopping-lists/${shoppingListId}/positions/${positionId}`, data);

export const usePutShoppingListPosition = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest & { positionId: number }>
) =>
  useMutation(
    (data: IRequest & { positionId: number }) =>
      putShoppingListPosition(shoppingListId, data.positionId, omit(data, 'positionId')),
    options
  );
