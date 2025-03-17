/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za reset hasła przy użuciu tokena

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  token: string;
  password: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postAuthPasswordReset = (data: IRequest): Promise<IResponse> =>
  axios.post('/auth/password-reset', data);

export const usePostAuthPasswordReset = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postAuthPasswordReset(data), options);
