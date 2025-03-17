// Hook odpowiedzialny za pobranie bloku html na stronę główną

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IHtmlBlockItem {
  html_code: string;
  section_field_symbol: string;
  value: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IHtmlBlockItem>;

const getHomeHtmlBlock = (
  sectionId: string,
  articleId: string,
  params?: IRequest
): Promise<IResponse> => axios.get(`/home/htmlblock/${sectionId}/${articleId}`, { params });

export const useGetHomeHtmlBlock = (
  sectionId: string,
  articleId: string,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['home-html-block', sectionId, articleId],
    () => getHomeHtmlBlock(sectionId, articleId, params),
    options
  );
