// Hook odpowiedzialny za pobranie listy produktw z typu subelementu strony głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse,
  IProductListItem
} from 'api/types';

// parametry requestu do api
type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IProductListItem>;

const getHomeSubelementItems = (subelementType: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/home/subelements/${subelementType}`, { params });

export const useGetHomeSubelementItems = (
  subelementType: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['home-subelement-items', subelementType],
    () => getHomeSubelementItems(subelementType, params),
    options
  );
