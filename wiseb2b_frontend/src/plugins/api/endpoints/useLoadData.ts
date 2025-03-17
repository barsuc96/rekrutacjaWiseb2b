// Hook odpowiedzialny za wywołanie akcji GET

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { IPaginationResponse, ICommandResponseError as IError } from 'api/types';

const isValidUrl = (str: string) => {
  if (str && !str.match('undefined')) {
    return true;
  }
};

// parametry requestu do api
export type IRequest = object;

// typ zwracanych danych
type IResponse = IPaginationResponse<any>;

const loadData = (command_url: string, params?: IRequest): Promise<IResponse> => {
  if (command_url && isValidUrl(command_url)) {
    return axios.get(command_url, { params });
  }

  return new Promise(() => null);
};

export const useLoadData = (
  command_url: string,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>([command_url, params], () => loadData(command_url, params), {
    ...options,
    cacheTime: 0
  });
