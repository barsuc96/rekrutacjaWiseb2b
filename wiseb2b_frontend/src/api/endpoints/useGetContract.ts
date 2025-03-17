// Hook odpowiedzialny za pobranie listy kontraktów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IContractItem {
  id: number;
  name: string;
  content: string;
  requirement: number;
  impact: number;
  contexts: 'HOME_PAGE' | 'CHECKOUT';
  testimony: string;
  symbol: string;
  type: string;
  roles: string;
  status: number;
  from_date: string | null;
  to_date: string;
  agree_ip: string | null;
  agree_date: string | null;
  disagree_ip: string | null;
  disagree_date: string | null;
  has_active_agree: boolean;
  user_must_accept: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest & {
  context?: 'HOME_PAGE' | 'CHECKOUT';
  currentContext?: 'HOME_PAGE' | 'CHECKOUT';
  onlyMustAccept?: boolean;
  cartId?: number;
};

// typ zwracanych danych
type IResponse = IPaginationResponse<IContractItem>;

const getContract = (params?: IRequest): Promise<IResponse> => axios.get('/contract', { params });

export const useGetContract = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['contract', params], () => getContract(params), options);
