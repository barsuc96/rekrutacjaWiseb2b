// Hook odpowiedzialny za pobranie szczegółów kategorii

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  id: number;
  name: string;
  products_count: number;
}

const getCategory = (id: number): Promise<IResponse> => axios.get(`/categories/${id}`);

export const useGetCategory = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['category', id], () => getCategory(id), options);
