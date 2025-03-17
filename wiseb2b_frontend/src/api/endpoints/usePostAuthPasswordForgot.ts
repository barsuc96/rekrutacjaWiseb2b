/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za rozpoczęcie procesu resetu hasła

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  email: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postAuthPasswordForgot = (data: IRequest): Promise<IResponse> =>
  axios.post('/auth/password-forgot', data);

export const usePostAuthPasswordForgot = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postAuthPasswordForgot(data), options);
