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

const postCartImport = (data: IRequest): Promise<IResponse> => axios.post('/carts/import', data);

export const usePostCartImport = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postCartImport(data), options);
