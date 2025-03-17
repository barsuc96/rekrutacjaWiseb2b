// Hook odpowiedzialny za tworzenie klienta

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postOverLogout = (): Promise<IResponse> => axios.post('/auth/overlogout', {});

export const usePostOverLogout = (options?: UseMutationOptions<IResponse, IError>) =>
  useMutation(() => postOverLogout(), options);
