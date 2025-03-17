/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie artykułu cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  name: string;
  description: string;
  base64: string;
}

// typ zwracanych danych
interface IResponse extends ICommandResponseSuccess {
  data: {
    url: string;
  };
}

const postCmsMedia = (data: IRequest): Promise<IResponse> =>
  axios.post('/cms/media', data, { env: { FormData: { isForm: true } as any } });

export const usePostCmsMedia = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postCmsMedia(data), options);
