/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za edycję pól sekcji cms

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

const putCmsSectionField = (
  sectionId: number,
  fieldId: number,
  data: IRequest
): Promise<IResponse> =>
  axios.put(`/cms/section/${sectionId}/fields/${fieldId}`, data, {
    env: { FormData: { isForm: true } as any }
  });

export const usePutCmsSectionField = (
  sectionId: number,
  fieldId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCmsSectionField(sectionId, fieldId, data), options);
