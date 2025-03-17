// Hook odpowiedzialny za akceptację/odrzucenie zgody

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  type: number;
  granted: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putUserAgreement = (userId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/users/${userId}/agreements`, data);

export const usePutUserAgreement = (
  userId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putUserAgreement(userId, data), options);
