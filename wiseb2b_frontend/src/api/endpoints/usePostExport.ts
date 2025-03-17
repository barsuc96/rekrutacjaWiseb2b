// Hook odpowiedzialny za tworzenie exportu

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
  data: {
    id: number;
  };
}

const postExport = (data: IRequest): Promise<IResponse> => axios.post('/exports', data);

export const usePostExport = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postExport(data), options);
