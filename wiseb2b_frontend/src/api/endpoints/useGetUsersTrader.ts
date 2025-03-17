// Hook odpowiedzialny za pobranie profilu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
}

const getUsersTrader = (): Promise<IResponse> => axios.get('/users/trader');

export const useGetUsersTrader = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>('users-trader', getUsersTrader, options);
