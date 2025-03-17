// Hook odpowiedzialny za pobranie listy kategorii

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICategoryListItem,
  ICommandResponseError as IError,
  IFilterAttributesRequest,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest & ISearchRequest & IFilterAttributesRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICategoryListItem>;

const getSearchCategories = (params?: IRequest): Promise<IResponse> =>
  axios.get('/search/categories', { params });

export const useGetSearchCategories = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['search-categories', params],
    () => getSearchCategories(params),
    options
  );
