// Hook odpowiedzialny za pobranie listy exportów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
export interface IRequest {
  exportType?: 'pdf' | 'xls' | 'csv';
}

// typ zwracanych danych
export interface IResponse {
  content: string;
  file_name: string;
  table_prefix: string;
}

const getCartExport = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/carts/${id}/export`, { params });

export const useGetCartExport = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-export', id, params],
    () => getCartExport(id, params),
    options
  );
