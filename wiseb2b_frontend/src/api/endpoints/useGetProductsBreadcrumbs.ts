// Hook odpowiedzialny za pobranie gradcrumbs'ów produktu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IPaginationRequest,
  IPaginationResponse
} from 'api/types';

// parametry requestu do api
export type IRequest = IPaginationRequest & {
  categoryId?: number;
  searchKeyword?: string;
};

interface IBreadcrumb {
  id: number;
  name: string;
  category_id: number;
  url: string;
}

// typ zwracanych danych
type IResponse = IPaginationResponse<IBreadcrumb>;

const getProductsBreadcrumbs = (params: IRequest): Promise<IResponse> =>
  axios.get(`/products/breadcrumbs`, { params });

export const useGetProductsBreadcrumbs = (
  params: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['products-breadcrumbs', params],
    () => getProductsBreadcrumbs(params),
    options
  );
