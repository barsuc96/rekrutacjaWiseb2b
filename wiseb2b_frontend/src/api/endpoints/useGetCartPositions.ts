// Hook odpowiedzialny za pobranie listy pozycji koszyka

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IImage,
  IPaginationRequest,
  IPaginationResponse,
  IPositionUnit,
  IStock
} from 'api/types';

export interface ICartPositionListItem {
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
  units: IPositionUnit[];
  url_link: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICartPositionListItem>;

const getCartPositions = (id: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/carts/${id}/positions`, { params });

export const useGetCartPositions = (
  id: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cart-positions', id, params],
    () => getCartPositions(id, params),
    options
  );
