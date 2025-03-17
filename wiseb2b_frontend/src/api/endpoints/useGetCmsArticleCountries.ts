// Hook odpowiedzialny za pobranie listy krajów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsArticleCountryListItem {
  code: string;
  name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsArticleCountryListItem>;

const getCmsArticleCountries = (params?: IRequest): Promise<IResponse> =>
  axios.get('/cms/article/countries', { params });

export const useGetCmsArticleCountries = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['article-countries', params],
    () => getCmsArticleCountries(params),
    options
  );
