// Hook odpowiedzialny za zmianę zgód

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IContractItem {
  id: 1;
  testimony: string;
  content: string;
  agreeDate: string;
  disagreeDate: string;
  has_active_agree: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IContractItem>;

const getUserContract = (params?: IRequest): Promise<IResponse> =>
  axios.get('/users/contract', { params });

export const useGetUserContract = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['user-contract', params], () => getUserContract(params), options);
