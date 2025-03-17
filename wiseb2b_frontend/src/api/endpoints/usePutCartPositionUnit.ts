// Hook odpowiedzialny za zmianę jednostki pozycji w koszyku

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { omit } from 'lodash';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

// parametry requestu do api
interface IRequest {
  unit_id: number;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putCartPositionUnit = (
  cartId: number,
  positionId: number,
  data: IRequest
): Promise<IResponse> => axios.put(`/carts/${cartId}/positions/${positionId}/unit`, data);

export const usePutCartPositionUnit = (
  cartId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) =>
  useMutation(
    (data: IRequest & { positionId: number }) =>
      putCartPositionUnit(cartId, data.positionId, omit(data, 'positionId')),
    options
  );
