// Hook odpowiedzialny za pobranie podsumowania listy dokumentów

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
type IResponse = Record<
  'proforma' | 'invoices' | 'corrections',
  { name: string; counter: number; icon: string }
>;

const getDocumentsSummary = (): Promise<IResponse> => axios.get('/documents/summary');

export const useGetDocumentsSummary = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['documents-summary'], () => getDocumentsSummary(), options);
