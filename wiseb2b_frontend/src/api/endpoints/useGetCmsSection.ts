// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IResponse {
  id: number;
  symbol: string;
  name: string;
  description: string;
  is_active: boolean;
}

const getCmsSection = (sectionId: number): Promise<IResponse> =>
  axios.get(`/cms/section/${sectionId}`);

export const useGetCmsSection = (sectionId: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['section', sectionId], () => getCmsSection(sectionId), options);
