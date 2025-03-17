// Hook odpowiedzialny za danych do odliczania do końca promocji na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
export interface IResponse {
  id: number;
  finish_datetime: string;
  title: string;
}

const getHomePromotionCountdown = (): Promise<IResponse> => axios.get('home/promotion-countdown');

export const useGetHomePromotionCountdown = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(
    ['home-promotion-countdown'],
    () => getHomePromotionCountdown(),
    options
  );
