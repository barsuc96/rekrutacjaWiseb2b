/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  symbol: string;
}

// typ zwracanych danych
interface IResponse extends ICommandResponseSuccess {
  data: {
    id: number;
  };
}

const postCart = (data: IRequest): Promise<IResponse> => axios.post('/carts', data);

export const usePostCart = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postCart(data), options);
