// Hook odpowiedzialny za wygenerowanie pliku exportu

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  ICommandResponseSuccess,
  IExportRequest
} from 'api/types';

// parametry requestu do api
type IRequest = Partial<IExportRequest>;

// typ zwracanych danych
interface IResponse extends ICommandResponseSuccess {
  data: { file_name: string; url: string };
}

const postExportGenerate = (data: IRequest): Promise<IResponse> =>
  axios.post('/exports/generate', data);

export const usePostExportGenerate = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postExportGenerate(data), options);
