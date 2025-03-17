// Hook odpowiedzialny za zmianę statusu użytkownika

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  enabled: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putUserMessageSettings = (
  userId: number,
  messageSettingsId: number,
  data: IRequest
): Promise<IResponse> => axios.put(`/users/${userId}/message-settings/${messageSettingsId}`, data);

export const usePutUserMessageSettings = (
  userId: number,
  messageSettingsId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) =>
  useMutation((data: IRequest) => putUserMessageSettings(userId, messageSettingsId, data), options);
