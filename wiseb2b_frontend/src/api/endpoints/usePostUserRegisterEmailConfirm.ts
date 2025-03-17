// Hook odpowiedzialny za zmianę danych w profilu

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

export interface IRequest {
  hash: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postUserRegisterEmailConfirm = (data: IRequest): Promise<IResponse> =>
  axios.post('/users/register-email-confirm', data);

export const usePostUserRegisterEmailConfirm = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postUserRegisterEmailConfirm(data), options);
