// Hook odpowiedzialny za import produktów do nowego koszyka

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  file: string;
  name: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCartImport = (cartId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/carts/${cartId}/import`, data);

export const usePutCartImport = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putCartImport(cartId, data), options);
