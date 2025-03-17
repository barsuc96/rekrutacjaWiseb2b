// Hook odpowiedzialny za pobranie listy języków

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ILanguageListItem {
  id: string;
  name: string;
  title_website: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ILanguageListItem>;

const getLayoutLanguages = (params?: IRequest): Promise<IResponse> =>
  axios.get('/layout/languages', { params });

export const useGetLayoutLanguages = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['layout-languages', params],
    () => getLayoutLanguages(params),
    options
  );
