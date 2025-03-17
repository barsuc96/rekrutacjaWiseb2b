// Hook odpowiedzialny za pobranie listy do slider'a w banerze na stronie głównej

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError, IProductListItem } from 'api/types';

// typ zwracanych danych
interface IResponse {
  name: string;
  image?: string;
  see_also?: string;
  products: IProductListItem[];
}

const getHomeProductsBox = (productBoxId: string): Promise<IResponse> =>
  axios.get(`/home/products/${productBoxId}`);

export const useGetHomeProductsBox = (
  productBoxId: string,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['home-products-box', productBoxId],
    () => getHomeProductsBox(productBoxId),
    options
  );
