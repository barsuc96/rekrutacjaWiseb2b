/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie użytkownika

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

const requestKeys = ['trader_id', 'email', 'first_name', 'last_name'];

// parametry requestu do api
export interface IRequest {
  trader_id?: number;
  email: string;
  first_name: string;
  last_name: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postUsers = (data: IRequest): Promise<IResponse> =>
  axios.post('/users', data, { env: { FormData: { requestKeys: requestKeys } as any } });

export const usePostUsers = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postUsers(data), options);
