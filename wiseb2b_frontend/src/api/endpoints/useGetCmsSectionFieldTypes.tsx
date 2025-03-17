// Hook odpowiedzialny za pobranie typów pól sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface ICmsSectionFieldType {
  label: string;
  value: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
export type IResponse = IPaginationResponse<ICmsSectionFieldType>;

const getCmsSectionFieldTypes = (params?: IRequest): Promise<IResponse> =>
  axios.get('/cms/section/field-types', { params });

export const useGetCmsSectionFieldTypes = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cms-sections-field-types'],
    () => getCmsSectionFieldTypes(params),
    options
  );
