// Hook odpowiedzialny za zmianę danych w profilu

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

const requestKeys = [
  'process_flags',
  'process_only_check',
  'type',
  'first_name',
  'last_name',
  'company_name',
  'tax_number',
  'phone',
  'email',
  'password',
  'website',
  'recaptcha_token',
  'billing_address.name',
  'billing_address.street',
  'billing_address.building',
  'billing_address.apartment',
  'billing_address.postal_code',
  'billing_address.city',
  'billing_address.state',
  'billing_address.country',
  'billing_address.country_code',
  'receiver_address.name',
  'receiver_address.street',
  'receiver_address.building',
  'receiver_address.apartment',
  'receiver_address.postal_code',
  'receiver_address.city',
  'receiver_address.state',
  'receiver_address.country',
  'receiver_address.country_code',
  'agreements'
];

interface IAgreement {
  type: string;
  accepted: boolean;
}

// parametry requestu do api
export interface IRequest {
  process_flags?: string;
  process_only_check?: boolean;
  type?: string;
  first_name: string;
  last_name: string;
  company_name?: string;
  tax_number: string;
  phone: string;
  email: string;
  password?: string;
  website?: string;
  recaptcha_token: string;
  billing_address?: {
    name: string;
    street: string;
    building: string;
    apartment: string;
    postal_code: string;
    city: string;
    state: string;
    country: string;
    country_code: string;
  };
  receiver_address?: {
    name: string;
    street: string;
    building: string;
    apartment: string;
    postal_code: string;
    city: string;
    state: string;
    country: string;
    country_code: string;
  };
  agreements?: IAgreement[];
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const postUserRegister = (data: IRequest): Promise<IResponse> =>
  axios.post('/users/register', data, { env: { FormData: { requestKeys: requestKeys } as never } });

export const usePostUserRegister = (options?: UseMutationOptions<IResponse, IError, IRequest>) =>
  useMutation((data: IRequest) => postUserRegister(data), options);
