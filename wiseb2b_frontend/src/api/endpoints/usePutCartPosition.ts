// Hook odpowiedzialny za aktualozację pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { omit } from 'lodash';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  quantity: number;
  unit_id: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCartPosition = (cartId: number, positionId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/carts/${cartId}/positions/${positionId}`, data);

export const usePutCartPosition = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) =>
  useMutation(
    (data: IRequest & { positionId: number }) =>
      putCartPosition(cartId, data.positionId, omit(data, 'positionId')),
    options
  );
