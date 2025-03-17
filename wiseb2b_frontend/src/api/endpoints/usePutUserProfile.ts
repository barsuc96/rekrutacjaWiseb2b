/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za zmianę danych profilowych

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

const requestKeys = [
  'first_name',
  'last_name',
  'email',
  'customer.name',
  'customer.address.street',
  'customer.address.building',
  'customer.address.postal_code',
  'customer.address.city',
  'customer.address.country',
  'customer.nip',
  'customer.email',
  'customer.phone'
];

// parametry requestu do api
interface IRequest {
  first_name: string;
  last_name: string;
  email: string;
  customer: {
    name: string;
    address: {
      street: string;
      building: string;
      postal_code: string;
      city: string;
      country: string;
    };
    nip: string;
    email: string;
    phone: string;
  };
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putUserProfile = (userId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/users/profile/${userId}`, data, {
    env: { FormData: { requestKeys: requestKeys } as any }
  });

export const usePutUserProfile = (
  userId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putUserProfile(userId, data), options);
