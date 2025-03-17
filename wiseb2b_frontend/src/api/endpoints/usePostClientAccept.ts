// Hook odpowiedzialny za akceptowanie klienta

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

export interface IRequest {
  client_id?: number;
  status?: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postClientAccept = (clientId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/clients/${clientId}/accept`, data);

export const usePostClientAccept = (
  clientId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postClientAccept(clientId, data), options);
