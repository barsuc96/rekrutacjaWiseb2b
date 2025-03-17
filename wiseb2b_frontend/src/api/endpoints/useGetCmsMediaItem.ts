// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
type IResponse = {
  id: number;
  name: string;
  description: string;
  url: string;
};

const getCmsMediaItem = (id: number): Promise<IResponse> => axios.get(`/cms/media/${id}`);

export const useGetCmsMediaItem = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['section-mediaItem', id], () => getCmsMediaItem(id), options);
