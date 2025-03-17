// Hook odpowiedzialny za pobranie url resolvera

import axios from 'api/axios';
import { useMutation, UseMutationOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

// parametry requestu do api
export type IRequest = {
  urlKey: string;
  fromLanguage: string;
  toLanguage: string;
};

export interface IResponse {
  url_shortcut: string;
  page_type: 'PRODUCTS_LIST' | 'PRODUCT_DETAILS' | 'SUBPAGE';
  parameters: string;
}

const getLayoutUrlResolverToLanguage = (params?: IRequest): Promise<IResponse> => {
  return axios.get('/layout/url_resolver-to-language', {
    params,
    headers: { 'Content-Language': params?.fromLanguage || '' }
  });
};
export const useGetLayoutUrlResolverToLanguage = (
  options?: UseMutationOptions<IResponse, IError, IRequest>
) => useMutation((params: IRequest) => getLayoutUrlResolverToLanguage(params), options);
