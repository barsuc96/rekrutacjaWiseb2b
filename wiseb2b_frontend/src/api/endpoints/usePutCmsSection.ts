// Hook odpowiedzialny za edycję sekcji cms

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  symbol: string;
  name: string;
  description: string;
  is_active: boolean;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCmsSection = (sectionId: number | undefined, data: IRequest): Promise<IResponse> =>
  axios.put(`/cms/section/${sectionId}`, data);

export const usePutCmsSection = (
  sectionId: number | undefined,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCmsSection(sectionId, data), options);
