// Hook odpowiedzialny za pobranie listy ofert dedykowanych

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest,
  IProductListItem
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest & ISearchRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getOffersDedicated = (params?: IRequest): Promise<IResponse> =>
  axios.get('/offers/dedicated', { params });

export const useGetOffersDedicated = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['offers-dedicated', params],
    () => getOffersDedicated(params),
    options
  );
