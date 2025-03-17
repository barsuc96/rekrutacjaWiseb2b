// Hook odpowiedzialny za pobranie listy typów subelementów na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IHomeSubelementType {
  id: number;
  name: string;
}

// parametry requestu do api
type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IHomeSubelementType>;

const getHomeSubelementsTypes = (params?: IRequest): Promise<IResponse> =>
  axios.get('/home/subelements-types', { params });

export const useGetHomeSubelementsTypes = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['home-subelements-types', params],
    () => getHomeSubelementsTypes(params),
    options
  );
