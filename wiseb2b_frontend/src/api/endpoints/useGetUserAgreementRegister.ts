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
  name: string;
  content: string;
  testimony: string;
  requirement: number;
  impact: number;
  contexts: 'HOME_PAGE' | 'CHECKOUT';
  symbol: string;
  type: 'RULES';
  roles: 'ROLE_USER_MAIN' | 'ROLE_USER';
  status: number;
  fromDate: string;
  toDate: string;
  agreeIp: string;
  agreeDate: string;
  disagreeIp: string;
  disagreeDate: string;
  has_active_agree: boolean;
  user_must_accept: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IAgreementListItem>;

const getUserAgreementRegister = (params?: IRequest): Promise<IResponse> =>
  axios.get(`/users/agreement/register`, { params });

export const useGetUserAgreementRegister = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['user-agreement-register', params],
    () => getUserAgreementRegister(params),
    options
  );
