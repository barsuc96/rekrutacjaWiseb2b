// Hook odpowiedzialny za inicjowanie płatności online

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
export interface IRequest {
  context: 'ORDER_CREATED';
  entity_type: 'ORDER';
  entity_id: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess & {
  data: {
    redirect_payload: string;
    redirect_type: 'REDIRECT_URL' | 'HTML';
  };
};

const postOnlinePaymentInitialize = (data: IRequest): Promise<IResponse> =>
  axios.post('/onlinepayment/initialize', data);

export const usePostOnlinePaymentInitialize = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postOnlinePaymentInitialize(data), options);
