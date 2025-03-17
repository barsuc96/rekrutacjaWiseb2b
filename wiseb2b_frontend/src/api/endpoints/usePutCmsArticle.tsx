// Hook odpowiedzialny za edycję artykułu cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  ICommandResponseSuccess,
  ICmsArticleRequest
} from 'api/types';

type IRequest = ICmsArticleRequest;

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCmsArticle = (articleId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/cms/article/${articleId}`, data);

export const usePutCmsArticle = (
  articleId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCmsArticle(articleId, data), options);
