// Hook odpowiedzialny za pobranie tokenu api

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// typ zwracanych danych
type IResponse = {
  client_id: string;
  client_secret: string;
};

const getTokenSecurity = (clientId: number): Promise<IResponse> =>
  axios.get(`/clients/${clientId}/client-api`);

export const useGetTokenSecurity = (
  clientId: number,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    [`token-security-${clientId}`],
    () => getTokenSecurity(clientId),
    options
  );
