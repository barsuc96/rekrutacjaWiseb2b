// Hook odpowiedzialny za pobranie listy pozycji zamówienia

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IImage,
  IStock,
  IPositionUnit
} from 'api/types';

export interface IOrderPositionListItem {
  id: number;
  index: string;
  stock: IStock;
  currency: string;
  image: IImage[];
  name: string;
  price_gross: number;
  price_gross_formatted: string;
  price_net: number;
  price_net_formatted: string;
  product_id: number;
  quantity: number;
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
export type IRequest = IPaginationRequest & ISearchRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IOrderPositionListItem>;

const getPanelOrderPositions = (orderId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/panel/orders/${orderId}/positions`, { params });

export const useGetPanelOrderPositions = (
  orderId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['order-positions', orderId, params],
    () => getPanelOrderPositions(orderId, params),
    options
  );
