/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie sekcji cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  symbol: string;
  name: string;
  description: string;
  is_active: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCmsSection = (data: IRequest): Promise<IResponse> =>
  axios.post('/cms/section', data, { env: { FormData: { isForm: true } as any } });

export const usePostCmsSection = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postCmsSection(data), options);
