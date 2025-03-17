// Hook odpowiedzialny za pobranie listy pozycji listy zakupowej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IImage,
  IPaginationRequest,
  IPaginationResponse,
  IStock,
  IUnit
} from 'api/types';

export interface IShoppingListPositionListItem {
  id: number;
  image: IImage[];
  index: string;
  name: string;
  price_gross: number;
  price_gross_formatted: string;
  price_net: number;
  price_net_formatted: string;
  product_id: number;
  quantity: number;
  currency: string;
  stock: IStock;
  total_price_gross: number;
  total_price_gross_formatted: string;
  total_price_net: number;
  total_price_net_formatted: string;
  unit_id: number;
  unit_price_gross: number;
  unit_price_gross_formatted: string;
  unit_price_net: number;
  unit_price_net_formatted: string;
  units: IUnit[];
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IShoppingListPositionListItem>;

const getGetShoppingListPositions = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/shopping-lists/${id}/positions`, { params });

export const useGetShoppingListPositions = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['shopping-list-positions', id, params],
    () => getGetShoppingListPositions(id, params),
    options
  );
