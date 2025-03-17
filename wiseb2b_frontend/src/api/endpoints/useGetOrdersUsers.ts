// Hook odpowiedzialny za pobranie listy użytkowników w zamówieniach

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IPaginationResponse } from 'api/types';

export interface IOrdersUserListItem {
  id: number;
  name: string;
}

// typ zwracanych danych
type IResponse = IPaginationResponse<IOrdersUserListItem>;

const getOrdersUsers = (): Promise<IResponse> => axios.get('/orders/users');

export const useGetOrdersUsers = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['orders-users'], () => getOrdersUsers(), options);
