// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationResponse,
  IPaginationRequest
} from 'api/types';

interface IArticleField {
  section_field_id: number;
  value: string;
  file: string | null;
}

export interface IArticleSection {
  section_id_symbol: string;
  symbol: string;
  id: number;
  title: string;
  layouts: string | null;
  article_fields: IArticleField[];
}

// typ zwracanych danych
export type IResponse = IPaginationResponse<IArticleSection>;

// parametry requestu do api
export type IRequest = IPaginationRequest & {
  fetchArticleFields: boolean;
};

const getCmsSectionArticles = (sectionSymbol: string, params?: IRequest): Promise<IResponse> =>
  axios.get(`/cms/articles/${sectionSymbol}`, { params });

export const useGetCmsSectionArticles = (
  sectionSymbol: string,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['section-articles', sectionSymbol, params],
    () => getCmsSectionArticles(sectionSymbol, params),
    options
  );
