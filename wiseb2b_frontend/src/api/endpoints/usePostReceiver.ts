/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za tworzenie odbiorcy

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

const requestKeys = [
  'name',
  'first_name',
  'last_name',
  'email',
  'phone',
  'address.street',
  'address.postal_code',
  'address.city',
  'address.building',
  'address.apartment',
  'address.country'
];

// parametry requestu do api
export interface IRequest {
  name: string;
  first_name: string;
  last_name: string;
  email: string;
  phone: string;
  address: {
    street: string;
    postal_code: string;
    city: string;
    building: string;
    apartment: string;
    country: string;
  };
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess & {
  data: {
    id: number;
  };
};

const postReceiver = (data: IRequest): Promise<IResponse> =>
  axios.post('/receivers', data, { env: { FormData: { requestKeys: requestKeys } as any } });

export const usePostReceiver = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postReceiver(data), options);
