// Hook odpowiedzialny za pobranie listy ofert okresowych

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

const getOffersPeriodic = (params?: IRequest): Promise<IResponse> =>
  axios.get('/offers/periodic', { params });

export const useGetOffersPeriodic = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['offers-periodic', params],
    () => getOffersPeriodic(params),
    options
  );
