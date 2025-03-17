// Hook odpowiedzialny za pobranie listy layoutów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsLayoutsItem {
  symbol: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsLayoutsItem>;

const getCmsLayouts = (params?: IRequest): Promise<IResponse> =>
  axios.get('/cms/layouts', { params });

export const useGetCmsLayouts = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cms-layouts', params], () => getCmsLayouts(params), options);
