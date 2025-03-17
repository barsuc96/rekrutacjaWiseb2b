// Hook odpowiedzialny za skasowanie sekcji

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCmsSection = (sectionId: number): Promise<IResponse> =>
  axios.delete(`/cms/section/${sectionId}`);

export const useDeleteCmsSection = (
  sectionId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteCmsSection(sectionId), options);
