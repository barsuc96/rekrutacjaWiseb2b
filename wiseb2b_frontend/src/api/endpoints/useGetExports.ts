// Hook odpowiedzialny za pobranie listy exportów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IExportListItem {
  id: number;
  name: string;
  type: string;
  last_generation_date?: string;
  next_generation_date: string;
  periodic: boolean;
  file_url: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IExportListItem>;

const getExports = (params?: IRequest): Promise<IResponse> => axios.get('/exports', { params });

export const useGetExports = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['exports', params], () => getExports(params), options);
