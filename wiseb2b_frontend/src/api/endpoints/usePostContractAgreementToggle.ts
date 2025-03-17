// Hook odpowiedzialny za tworzenie pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  items: {
    contractId: number;
    contextAgreement: 'HOME_PAGE' | 'CHECKOUT';
    cartId?: number;
    isAgree: boolean;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postContractAgreementToggle = (data: IRequest): Promise<IResponse> =>
  axios.post(`contract-agreement/toggle`, data);

export const usePostContractAgreementToggle = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postContractAgreementToggle(data), options);
