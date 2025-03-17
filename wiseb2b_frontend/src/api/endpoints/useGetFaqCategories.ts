// Hook odpowiedzialny za pobranie listy kategorii faq

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IFaqCategoryListItem {
  id: number;
  name: string;
  icon_url: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IFaqCategoryListItem>;

const getFaqCategories = (params?: IRequest): Promise<IResponse> =>
  axios.get('/faq/categories', { params });

export const useGetFaqCategories = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(['faq-categories', params], () => getFaqCategories(params), options);
