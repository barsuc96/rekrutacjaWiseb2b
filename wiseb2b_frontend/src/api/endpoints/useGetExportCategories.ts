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

const getExportCategories = (params?: IRequest): Promise<IResponse> =>
  axios.get('/exports/categories', { params });

export const useGetExportCategories = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['export-categories', params],
    () => getExportCategories(params),
    options
  );
