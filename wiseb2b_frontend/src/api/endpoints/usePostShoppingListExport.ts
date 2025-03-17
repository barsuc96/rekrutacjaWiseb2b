// Hook odpowiedzialny za export liście zakupowej do piku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
interface IRequest {
  export_type: 'CSV' | 'XLS';
}

// typ zwracanych danych
type IResponse = {
  file_name: string;
  content: string;
};

const postShoppingListExport = (shoppingListId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/shopping-lists/${shoppingListId}/export`, { params });

export const usePostShoppingListExport = (
  shoppingListId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postShoppingListExport(shoppingListId, data), options);
