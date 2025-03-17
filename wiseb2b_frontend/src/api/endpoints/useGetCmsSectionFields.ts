// Hook odpowiedzialny za pobranie listy pól sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsSectionItemFields {
  id: number;
  symbol: string;
  label: string;
  description: string;
  type: string;
  is_active: boolean;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<ICmsSectionItemFields>;

const getCmsSectionFields = (sectionId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/cms/section/${sectionId}/fields`, { params });

export const useGetCmsSectionFields = (
  sectionId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cms-sections-fields', sectionId],
    () => getCmsSectionFields(sectionId, params),
    options
  );
