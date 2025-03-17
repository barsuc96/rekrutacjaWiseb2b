/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie listy zakupowej

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  name: string;
  description: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess & {
  data: {
    id: number;
  };
};

const postShoppingList = (data: IRequest): Promise<IResponse> =>
  axios.post('/shopping-lists', data);

export const usePostShoppingList = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postShoppingList(data), options);
