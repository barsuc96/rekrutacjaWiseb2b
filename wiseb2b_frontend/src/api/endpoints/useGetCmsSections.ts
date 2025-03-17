// Hook odpowiedzialny za pobranie listy sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsSectionItem {
  id: number;
  lp: number;
  symbol: string;
  name: string;
  description: string;
  is_active: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsSectionItem>;

const getCmsSections = (params?: IRequest): Promise<IResponse> =>
  axios.get('/cms/section', { params });

export const useGetCmsSections = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['cms-sections', params], () => getCmsSections(params), options);
