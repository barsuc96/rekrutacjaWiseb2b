// Hook odpowiedzialny za zmianę statusu użytkownika

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  is_active: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putUserChangeStatus = (userId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/users/${userId}/change-status`, data);

export const usePutUserChangeStatus = (
  userId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putUserChangeStatus(userId, data), options);
