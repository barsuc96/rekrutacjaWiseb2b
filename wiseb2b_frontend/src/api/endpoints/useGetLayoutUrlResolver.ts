// Hook odpowiedzialny za pobranie url resolvera

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export interface IHrefLang {
  language: string;
  url_shortcut: string;
}

export interface IResponse {
  page_type: 'PRODUCTS_LIST' | 'PRODUCT_DETAILS' | 'SUBPAGE';
  parameters: string;
  href_lang: IHrefLang[];
}

// parametry requestu do api
export type IRequest = {
  urlKey: string;
};

const getLayoutUrlResolver = (params?: IRequest): Promise<IResponse> =>
  axios.get('/layout/url_resolver', { params });

export const useGetLayoutUrlResolver = (
  params?: IRequest,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['url-resolver', params],
    () => getLayoutUrlResolver(params),
    options
  );
