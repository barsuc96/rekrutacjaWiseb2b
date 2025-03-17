// Hook odpowiedzialny za pobranie listy exportów cyklicznych

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IExportPeriodicListItem {
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
type IResponse = IPaginationResponse<IExportPeriodicListItem>;

const getExportsPeriodic = (params?: IRequest): Promise<IResponse> =>
  axios.get('/exports/periodic', { params });

export const useGetExportsPeriodic = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['exports-periodic', params],
    () => getExportsPeriodic(params),
    options
  );
