// Hook odpowiedzialny za pobranie grupy listy artykułów na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
interface IResponse {
  id: string;
  title: string;
  articles: {
    id: number;
    title: string;
    background_picture?: string;
  }[];
}

const getHomeArticlesGroup = (groupId: string): Promise<IResponse> =>
  axios.get(`/home/articles/${groupId}`);

export const useGetHomeArticlesGroup = (
  groupId: string,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['home-articles-group', groupId],
    () => getHomeArticlesGroup(groupId),
    options
  );
