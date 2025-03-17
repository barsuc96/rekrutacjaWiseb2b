/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za zmianę hasła zalogowanego użytkownika

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  old_password: string;
  new_password: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postAuthPasswordChange = (data: IRequest): Promise<IResponse> =>
  axios.post('/auth/password-change', data);

export const usePostAuthPasswordChange = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postAuthPasswordChange(data), options);
