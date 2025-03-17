// Hook odpowiedzialny za pobranie sekcji cms

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IResponse {
  symbol: string;
  label: string;
  type: string;
  description: string;
  is_active: boolean;
}

const getCmsSectionField = (sectionId: number, fieldId: number): Promise<IResponse> =>
  axios.get(`/cms/section/${sectionId}/fields/${fieldId}`);

export const useGetCmsSectionField = (
  sectionId: number,
  fieldId: number,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['cms-sections-field', fieldId],
    () => getCmsSectionField(sectionId, fieldId),
    options
  );
