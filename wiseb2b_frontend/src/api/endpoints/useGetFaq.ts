// Hook odpowiedzialny za pobranie listy faq

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  ISearchRequest
} from 'api/types';

export interface IFaqListItem {
  id: number;
  category: {
    id: number;
  };
  question: string;
  answer: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest &
  ISearchRequest & {
    category_id?: number;
  };

// typ zwracanych danych
type IResponse = IPaginationResponse<IFaqListItem>;

const getFaq = (faqCategoryId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/faq/${faqCategoryId}`, { params });

export const useGetFaq = (
  faqCategoryId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(['faq', faqCategoryId], () => getFaq(faqCategoryId, params), options);
