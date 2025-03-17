// Hook odpowiedzialny za export zamówienia

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
interface IResponse {
  url: string;
  file_name: string;
}

const getOrderExportImages = (orderId: number): Promise<IResponse> =>
  axios.get(`/orders/${orderId}/export/images`);

export const useGetOrderExportImages = (
  orderId: number,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['order-export-images', orderId],
    () => getOrderExportImages(orderId),
    options
  );
