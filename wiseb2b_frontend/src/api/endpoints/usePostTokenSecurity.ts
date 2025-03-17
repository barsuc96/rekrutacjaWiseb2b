// Hook odpowiedzialny za generowanie tokena client api

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postTokenSecurity = (client_id: number): Promise<IResponse> =>
  axios.post(`/clients/${client_id}/client-api/generate-client-secret`);

export const usePostTokenSecurity = (
  client_id: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => postTokenSecurity(client_id), options);
