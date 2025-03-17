// Hook odpowiedzialny za pobranie regulaminu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export type IAgreement = {
  content: string;
  header: string;
  sub_header: string;
};

// typ zwracanych danych
type IResponse = {
  user_agreement: IAgreement;
  user_coookies_agreement: IAgreement;
  user_data_processing_agreement: IAgreement;
  user_rodo_agreement: IAgreement;
};

const getTerms = (): Promise<IResponse> => axios.get('/terms');

export const useGetTerms = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['terms'], () => getTerms(), options);
