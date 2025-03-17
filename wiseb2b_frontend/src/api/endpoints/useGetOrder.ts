// Hook odpowiedzialny za pobranie szczgółów zamówienia

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IStatus } from 'api/types';

export interface HistoryItem {
  date: string;
  hour: string;
  status_type: number;
  status_message: string;
}

// typ zwracanych danych
export interface IResponse {
  id: number;
  order_id: number;
  customer: {
    name: string;
    address: {
      street: string;
      postal_code: string;
      city: string;
    };
    nip: string;
    email: string;
    phone?: string;
  };
  receiver: {
    id: number;
    name: string;
    email: string;
    phone: string;
    address: {
      street: string;
      postal_code: string;
      city: string;
    };
  };
  create_date: string;
  status_type: number;
  status_message: string;
  value_net: number;
  value_net_formatted: string;
  value_gross: number;
  value_gross_formatted: string;
  currency: string;
  status_history: HistoryItem[];
  status: IStatus;
  name: string;
  message?: string;
}

const getOrder = (id: number): Promise<IResponse> => axios.get(`/orders/${id}`);

export const useGetOrder = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['order', id], () => getOrder(id), options);
