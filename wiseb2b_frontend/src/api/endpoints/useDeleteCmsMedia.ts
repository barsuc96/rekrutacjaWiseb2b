// Hook odpowiedzialny za skasowanie sekcji

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCmsMedia = (id: number): Promise<IResponse> => axios.delete(`/cms/media/${id}`);

export const useDeleteCmsMedia = (id: number, options?: UseMutationOptions<IResponse, IError>) =>
  useMutation(() => deleteCmsMedia(id), options);
