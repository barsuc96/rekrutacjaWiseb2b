// Hook odpowiedzialny za skasowanie sekcji

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCmsArticle = (articleId: number): Promise<IResponse> =>
  axios.delete(`/cms/article/${articleId}`);

export const useDeleteCmsArticle = (
  articleId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteCmsArticle(articleId), options);
