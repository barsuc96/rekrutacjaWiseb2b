// Hook odpowiedzialny za pobranie listy ustawień wiadomości

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

interface IMessageSettingListItem {
  id: number;
  message_settings_id: number;
  name: string;
  enabled: boolean;
}

// parametry requestu do api
type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IMessageSettingListItem>;

const getUserMessageSettings = (userId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/users/${userId}/message-settings`, { params });

export const useGetUserMessageSettings = (
  userId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['user-message-settings', userId],
    () => getUserMessageSettings(userId),
    options
  );
