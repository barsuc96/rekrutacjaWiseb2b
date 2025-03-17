// Hook odpowiedzialny za pobranie listy kategorii na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IPaginationResponse } from 'api/types';

export interface IHomeCategoryListItem {
  id: number;
  image: string;
  name: string;
}

// typ zwracanych danych
type IResponse = IPaginationResponse<IHomeCategoryListItem>;

const getHomeCategories = (): Promise<IResponse> => axios.get('/home/categories');

export const useGetHomeCategories = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['home-categories'], () => getHomeCategories(), options);
