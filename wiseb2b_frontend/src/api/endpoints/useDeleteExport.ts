// Hook odpowiedzialny za skasowanie exportu

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteExport = (exportId: number): Promise<IResponse> => axios.delete(`/exports/${exportId}`);

export const useDeleteExport = (
  exportId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteExport(exportId), options);
