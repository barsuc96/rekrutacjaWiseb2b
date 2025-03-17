// Hook odpowiedzialny za pobranie listy sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsMediaItem {
  id: number;
  lp: number;
  name: string;
  description: string;
  url: string;
  type: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsMediaItem>;

const getCmsMedia = (params?: IRequest): Promise<IResponse> => axios.get('/cms/media', { params });

export const useGetCmsMedia = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['cms-media', params], () => getCmsMedia(params), options);
