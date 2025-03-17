/* eslint-disable @typescript-eslint/no-explicit-any */
// Hook odpowiedzialny za aktualizację klienta

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError, ICommandResponseSuccess } from 'api/types';

const requestKeys = [
  'name',
  'street',
  'postal_code',
  'city',
  'country',
  'nip',
  'contact_person_first_name',
  'contact_person_last_name',
  'email',
  'phone'
];

// parametry requestu do api
interface IRequest {
  name: string;
  street: string;
  postal_code: string;
  city: string;
  country: string;
  nip: string;
  contact_person_first_name: string;
  contact_person_last_name: string;
  email: string;
  phone: string;
}

// typ zwracanych danych
type IResponse = ICommandResponseSuccess;

const putClient = (clientId: number, data: IRequest): Promise<IResponse> =>
  axios.put(`/clients/${clientId}`, data, {
    env: { FormData: { requestKeys: requestKeys } as any }
  });

export const usePutClient = (
  clientId: number,
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((data: IRequest) => putClient(clientId, data), options);
