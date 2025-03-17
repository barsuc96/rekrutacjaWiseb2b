// Hook odpowiedzialny za pobranie szczegółów exportu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IExportAttribute {
  id: number;
  name: string;
  field_name: string;
}

// typ zwracanych danych
export interface IResponse {
  id: number;
  name: string;
  type: string;
  last_generation_date?: string;
  next_generation_date: string;
  periodic: boolean;
  period: number;
  start_date?: string;
  attributes: IExportAttribute[];
  send_on_mail: boolean;
  csv_separator: string;
  url: string;
}

const getExport = (exportId: number): Promise<IResponse> => axios.get(`/exports/${exportId}`);

export const useGetExport = (exportId: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['export', exportId], () => getExport(exportId), options);
