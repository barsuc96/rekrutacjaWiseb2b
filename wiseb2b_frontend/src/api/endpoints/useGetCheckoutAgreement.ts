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
export type IRequest = IPaginationRequest & {
  cartId?: number;
};

// typ zwracanych danych
type IResponse = IPaginationResponse<IAgreementListItem>;

const getCheckoutAgreement = (params?: IRequest): Promise<IResponse> =>
  axios.get(`/checkout/agreement`, { params });

export const useGetCheckoutAgreement = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['checkout-agreement', params],
    () => getCheckoutAgreement(params),
    options
  );
