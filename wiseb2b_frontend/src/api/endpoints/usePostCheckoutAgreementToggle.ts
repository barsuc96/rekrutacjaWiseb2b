// Hook odpowiedzialny za akceptację zgóð w checkoucie

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  items: {
    contractId: number;
    cartId?: number;
    isAgree: boolean;
  }[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postCheckoutAgreementToggle = (data: IRequest): Promise<IResponse> =>
  axios.post(`checkout/agreement/toggle`, data);

export const usePostCheckoutAgreementToggle = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postCheckoutAgreementToggle(data), options);
