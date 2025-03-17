// Hook odpowiedzialny za pobranie listy sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsArticleItem {
  id: number;
  lp: number;
  symbol: string;
  title: string;
  is_active: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsArticleItem>;

const getCmsArticles = (params?: IRequest): Promise<IResponse> =>
  axios.get('/cms/article', { params });

export const useGetCmsArticles = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) => useQuery<IResponse, IError>(['cms-articles', params], () => getCmsArticles(params), options);
