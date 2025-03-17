// Hook odpowiedzialny za skasowanie pola sekcji

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const deleteCmsSectionField = (sectionId: number, fieldId: number): Promise<IResponse> =>
  axios.delete(`/cms/section/${sectionId}/fields/${fieldId}`);

export const useDeleteCmsSectionField = (
  sectionId: number,
  fieldId: number,
  options?: UseMutationOptions<IResponse, IError>
) => useMutation(() => deleteCmsSectionField(sectionId, fieldId), options);
