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

type ICategory = {
  categories_list?: ICategoryListItem[];
  label: string;
  show_category_button: boolean;
  style: null | string;
  url_link: null | string;
};

// typ zwracanych danych
type IResponse = IPaginationResponse<ICategory>;

const getLayoutCategories = (params?: IRequest): Promise<IResponse> =>
  axios.get('/layout/categories', { params });

export const useGetLayoutCategories = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['layout-categories', params],
    () => getLayoutCategories(params),
    options
  );
