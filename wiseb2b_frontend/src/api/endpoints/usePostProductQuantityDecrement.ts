// Hook odpowiedzialny za zmniejszenie ilości produktu

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  value: number;
  unit_id: number;
}

// typ zwracanych danych
interface IResponse extends ICommandResponseSuccess {
  data: {
    value: number;
    buy_button_active: boolean;
  };
}
const postProductQuantityDecrement = (productId: number, data: IRequest): Promise<IResponse> =>
  axios.post(`/products/${productId}/quantity-decrement`, data);

export const usePostProductQuantityDecrement = (
  productId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => postProductQuantityDecrement(productId, data), options);
