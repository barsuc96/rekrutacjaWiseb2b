// Hook odpowiedzialny za pobranie gradcrumbs'ów produktu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IRequest {
  categoryId?: number;
  searchKeywords?: string;
}

// typ zwracanych danych
interface IResponse {
  items: {
    id: number;
    name: string;
    category_id?: number;
    url?: string;
    url_link?: string;
  }[];
}

const getProductBreadcrumbs = (productId: number, params?: IRequest): Promise<IResponse> =>
  axios.get(`/products/${productId}/breadcrumbs`, { params });

export const useGetProductBreadcrumbs = (
  productId: number,
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['product-breadcrumbs', productId, params],
    () => getProductBreadcrumbs(productId, params),
    options
  );
