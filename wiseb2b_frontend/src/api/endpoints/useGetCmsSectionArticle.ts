// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IArticleSection } from 'api/types';

// typ zwracanych danych
type IResponse = IArticleSection;

const getCmsSectionArticle = (sectionId: string, articleId: string): Promise<IResponse> =>
  axios.get(`/cms/articles/${sectionId}/${articleId}`);

export const useGetCmsSectionArticle = (
  sectionId: string,
  articleId: string,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['section-article', sectionId],
    () => getCmsSectionArticle(sectionId, articleId),
    options
  );
