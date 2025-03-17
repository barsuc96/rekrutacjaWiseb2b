// Hook odpowiedzialny za pobranie szczegłów produktu

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import {
  ICommandResponseError as IError,
  IImage,
  IStock,
  IUnit,
  IProductLabel,
  ITechnicalAttribute
} from 'api/types';

export interface IFile {
  id: number;
  name: string;
  extension: string;
  url: string;
  label: string;
}

export interface IColor {
  product_id: number;
  name: string;
  color: string;
}

export interface IVersion {
  product_id: number;
  name: string;
}

export interface ILogisticAttribute {
  name: string;
  value: string;
  icon: string;
}

export interface IGpsrSupplierInfo {
  id: 1;
  symbol: string;
  tax_number: string;
  phone: string;
  email: string;
  address: {
    name: string;
    street: string;
    house_number: string;
    apartment_number: string;
    city: string;
    postal_code: string;
    country_code: string;
    country: string;
    state: string;
  };
  registered_trade_name: string;
}

// typ zwracanych danych
export interface IResponse {
  id: number;
  index: string;
  title: string;
  description: string;
  description_full: string;
  description_short: string;
  producer_name: string;
  stock: IStock;
  default_unit_id: number;
  units: IUnit[];
  files: IFile[];
  images: IImage[];
  labels: IProductLabel[];
  logistic_attributes: ILogisticAttribute[];
  technical_attributes: ITechnicalAttribute[];
  currency: string;
  colors: IColor[];
  versions: IVersion[];
  category_id: number;
  category_name: string;
  promotion?: {
    color: string;
    name: string;
    filters: string;
  };
  payload: {
    gpsr_supplier_info?: IGpsrSupplierInfo;
  };
}

const getProduct = (id: number): Promise<IResponse> => axios.get(`/products/${id}`);

export const useGetProduct = (id: number, options?: UseQueryOptions<IResponse, IError>) =>
  useQuery<IResponse, IError>(['product', id], () => getProduct(id), options);
