/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie artykułu cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  base64: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCmsMedia = (id: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/cms/media/${id}`, data, { env: { FormData: { isForm: true } as any } });

export const usePutCmsMedia = (
  id: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCmsMedia(id, data), options);
