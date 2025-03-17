// Hook odpowiedzialny za export zamówienia

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
export interface IRequest {
  export_type?: 'pdf' | 'xls' | 'csv';
}

// typ zwracanych danych
interface IResponse {
  content: string;
  file_name: string;
}

const getOrderExport = (orderId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/orders/${orderId}/export`, { params });

export const useGetOrderExport = (
  orderId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['order-export', orderId, params],
    () => getOrderExport(orderId, params),
    options
  );
