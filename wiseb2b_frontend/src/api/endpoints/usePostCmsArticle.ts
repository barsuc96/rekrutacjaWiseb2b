/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie artykułu cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

interface IArticleField {
  symbol: string;
  type: string;
  value: string;
  file: {
    base64: string;
    name: string;
  };
}

export interface ITranslatedTitle {
  language: string;
  translation: string;
}

// parametry requestu do api
export interface IRequest {
  symbol: string;
  title: ITranslatedTitle[] | null;
  language: string;
  from_date: string | null;
  to_date: string | null;
  is_active: boolean;
  layouts: string | null;
  article_fields: IArticleField[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCmsArticle = (data: IRequest): Promise<IResponse> =>
  axios.post('/cms/article', data, { env: { FormData: { isForm: true } as any } });

export const usePostCmsArticle = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postCmsArticle(data), options);
