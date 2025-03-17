// Hook odpowiedzialny za pobranie listy do slider'a w banerze na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IPaginationResponse } from 'api/types';

export interface IHomeSliderListItem {
  id: number;
  name: string;
  image: string;
  url: string;
  new_tab: boolean;
  category_id?: number;
  search_keyword?: string;
  sort_method?: string;
}

// typ zwracanych danych
type IResponse = IPaginationResponse<IHomeSliderListItem>;

const getHomeSlider = (): Promise<IResponse> => axios.get('/home/slider');

export const useGetHomeSlider = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['home-slider'], () => getHomeSlider(), options);
