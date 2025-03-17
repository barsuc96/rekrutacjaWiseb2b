/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za logowanie (stworzenie JWT)

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
interface IRequest {
  username: string;
  password: string;
  client_id: string;
  client_secret: string;
}

// typ zwracanych danych
// TODO standard command response?
interface IResponse {
  access_token: string;
  expires_in: number;
  refresh_token: string;
  token_type: string;
  message?: string;
  status: 0 | 1;
}

const postAuthLogin = (data: IRequest): Promise<IResponse> => axios.post('/auth/login', data);

export const usePostAuthLogin = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postAuthLogin(data), options);
