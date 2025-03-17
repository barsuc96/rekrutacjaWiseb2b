// Hook odpowiedzialny za pobranie listy zgód użytkownika

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IAgreementListItem {
  id: number;
  ip_address?: string;
  date?: string;
  content: string;
  type: number;
  necessary: boolean;
  accepted: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IAgreementListItem>;

const getUserAgreements = (userId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/users/${userId}/agreements`, { params });

export const useGetUserAgreements = (
  userId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['user-agreements', userId, params],
    () => getUserAgreements(userId, params),
    options
  );
