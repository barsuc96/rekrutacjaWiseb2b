// Hook odpowiedzialny za pobranie statusu płatności online

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  description_html: string | null;
  payment_status: number;
  payment_status_label: string;
  table_prefix: string;
}

const getOnlinePaymentStatus = (id: string): Promise<IResponse> =>
  axios.get(`/onlinepayment/${id}`);

export const useGetOnlinePaymentStatus = (
  id: string,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['onlinepayment-status', id],
    () => getOnlinePaymentStatus(id),
    options
  );
