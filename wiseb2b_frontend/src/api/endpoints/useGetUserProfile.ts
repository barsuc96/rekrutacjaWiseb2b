// Hook odpowiedzialny za pobranie profilu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IUserAgreementListItem } from 'api/types';

export type IUserRoles =
  | 'ROLE_USER_MAIN'
  | 'ROLE_USER'
  | 'ROLE_TRADER'
  | 'ROLE_ADMIN'
  | 'ROLE_OPEN_PROFILE';

// typ zwracanych danych
export interface IResponse {
  id: number;
  change_password: boolean;
  consents_required: boolean;
  first_name: string;
  last_name: string;
  email: string;
  overloged: false;
  logged_user: {
    first_name: string;
    id: number;
    last_name: string;
    role: string;
  };
  role: IUserRoles;
  sale_supervisor: {
    name: string;
    phone: string;
    email: string;
  };
  agreements: IUserAgreementListItem[];
  customer: {
    name: string;
    address: {
      street: string;
      house_number: string;
      postal_code: string;
      city: string;
      country: string;
    };
    nip: string;
    email: string;
    phone?: string;
  };
}

const getUserProfile = (): Promise<IResponse> => axios.get('/users/profile');

export const useGetUserProfile = (options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>('profile', getUserProfile, options);
