// Hook odpowiedzialny za wywołanie akcji POST

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
export type IRequest = object;

// typ zwracanych danych
interface IResponse {
  data: object;
}

const callCommand = (command_url: string, data: IRequest): Promise<IResponse> =>
  axios.post(command_url, data);

export const useCallCommand = (
  command_url: string,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => callCommand(command_url, data), options);
