/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie pól sekcji cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  symbol: string;
  label: string;
  type: string;
  description: string;
  is_active: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCmsSectionField = (sectionId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/cms/section/${sectionId}/fields`, data, {
    env: { FormData: { isForm: true } as any }
  });

export const usePostCmsSectionField = (
  sectionId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postCmsSectionField(sectionId, data), options);
