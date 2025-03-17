// Hook odpowiedzialny za pobranie listy atrybutów exportu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IExportAttributeListItem {
  id: number;
  name: string;
  field_name: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest;

// typ zwracanych danych
type IResponse = IPaginationResponse<IExportAttributeListItem>;

const getExportAttributes = (params?: IRequest): Promise<IResponse> =>
  axios.get('/exports/attributes', { params });

export const useGetExportAttributes = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['export-attributes', params],
    () => getExportAttributes(params),
    options
  );
