// Hook odpowiedzialny za pobranie listy list zakupowych

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IShoppingListListItem {
  id: number;
  lp: number;
  name: string;
  description: string;
  create_datetime: string;
  products_count: number;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IShoppingListListItem>;

const getShoppingLists = (params?: IRequest): Promise<IResponse> =>
  axios.get('/shopping-lists', { params });

export const useGetShoppingLists = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(['shopping-lists', params], () => getShoppingLists(params), options);
