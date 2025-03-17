// Hook odpowiedzialny za pobranie listy kategorii

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest & {
  categoryId?: number;
};

export type IFilterCategoryListItem = {
  id: number;
  name: string;
  url_link: string;
};

// typ zwracanych danych
type IResponse = IPaginationResponse<IFilterCategoryListItem>;

const getSearchFilterCategoryList = (params?: IRequest): Promise<IResponse> =>
  axios.get('/search/filter-category-list', { params });

export const useGetSearchFilterCategoryList = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['search-filter-category-list', params],
    () => getSearchFilterCategoryList(params),
    options
  );
