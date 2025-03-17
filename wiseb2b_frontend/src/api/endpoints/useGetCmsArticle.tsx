// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

interface IFile {
  base64: string;
  name: string;
  url: string;
}

export interface IArticleField {
  section_field_id: number;
  symbol: string;
  type: string;
  value: any;
  file: IFile;
}

export interface ITranslatedTitle {
  language: string;
  translation: string;
}

export interface ITranslatedArticleField {
  language: string;
  translation: string;
}

export interface IResponse {
  id: number;
  symbol: string;
  title: ITranslatedTitle[];
  language: string;
  from_date: string | null;
  to_date: string | null;
  is_active: boolean;
  section_id: number;
  layouts: string | null;
  article_fields: IArticleField[];
}

// parametry requestu do api
type IRequest = {
  language: string;
};

const getCmsArticle = (articleId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/cms/article/${articleId}`, { params });

export const useGetCmsArticle = (
  articleId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['article', articleId],
    () => getCmsArticle(articleId, params),
    options
  );
