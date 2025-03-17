// Hook odpowiedzialny za aktualizację exportu

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
type IResponse = ICommandResponseSuccess;

const putExport = (exportId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/exports/${exportId}`, data);

export const usePutExport = (
  exportId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putExport(exportId, data), options);
