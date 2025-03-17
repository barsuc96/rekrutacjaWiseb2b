// Hook odpowiedzialny za tworzenie klienta

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  to_switch_user_id: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postOverLogin = (data: IRequest): Promise<IResponse> => axios.post('/auth/overlogin', data);

export const usePostOverLogin = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postOverLogin(data), options);
