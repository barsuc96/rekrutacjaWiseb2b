// Hook odpowiedzialny za pobranie listy dokumentów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

export interface IDocumentListItem {
  id: number;
  symbol: string;
  currency: string;
  payment_datetime: string;
  create_datetime: string;
  value_gross: number;
  value_gross_formatted: string;
}

// parametry requestu do api
export type IRequest = IPaginationRequest & {
  document_type: 'PROFORMA' | 'INVOICE' | 'CORRECTIVE_INVOICE';
};

// typ zwracanych danych
type IResponse = IPaginationResponse<IDocumentListItem>;

const getDocuments = (params?: IRequest): Promise<IResponse> => axios.get('/documents', { params });

export const useGetDocuments = (params?: IRequest, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['documents', params], () => getDocuments(params), options);
