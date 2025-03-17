// Hook odpowiedzialny za pobranie pliku exportu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  content: string;
  file: string;
}

const getExportDownload = (hash: string): Promise<IResponse> =>
  axios.get(`/exports/download/${hash}`);

export const useGetExportDownload = (hash: string, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['download-export', hash], () => getExportDownload(hash), options);
