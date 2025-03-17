// Hook odpowiedzialny za zmianę zgód

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  items: {
    contractId: number;
    contextAgreement: 'DASHBOARD_USER_CONTRACT';
    isAgree: boolean;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postUserContractToggle = (data: IRequest): Promise<IResponse> =>
  axios.post(`/users/contract/toggle`, data);

export const usePostUserContractToggle = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postUserContractToggle(data), options);
