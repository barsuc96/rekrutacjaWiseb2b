// wspólne typy używane w kilku endpointach

import { ReactNode } from 'react';

export interface ITechnicalAttribute {
  name: string;
  value: string;
  type: 'string' | 'boolean';
  highlighted: boolean;
}

export interface IImage {
  min: string;
  thumb: string;
  big: string;
}

export interface IProductsFilterValue {
  value: string;
  name: string;
}

export interface IProductsFilter {
  id: string;
  type: 'singlechoice' | string;
  values: IProductsFilterValue[];
  label: string;
}

export interface IProductLabel {
  type: 'PROMOTION' | 'BESTSELLER' | 'NEW';
  name: string;
}

export interface IStock {
  value: number;
  name: string | ReactNode;
  type: 'low-stock' | 'middle-stock' | 'big-stock';
}

export interface IUnit {
  id: number;
  unit_id: number;
  name: string;
  converter: number;
  converter_message: string;
  price_net: number;
  price_net_formatted: string;
  old_price_net?: number;
  old_price_net_formatted?: string;
  price_gross: number;
  price_gross_formatted: string;
  old_price_gross?: number;
  old_price_gross_formatted?: string;
  unit_price_net: number;
  unit_price_net_formatted: string;
  unit_price_gross: number;
  unit_price_gross_formatted: string;
  minimal_quantity?: number;
  style: 'NORMAL' | 'SPECIAL';
}

export interface IPositionUnit {
  id: number;
  unit_id: number;
  name: string;
  converter: number;
  converter_message?: string;
}

export interface IProductListItem {
  id: number;
  index: string;
  stock: IStock;
  currency: string;
  images: IImage[];
  units: IUnit[];
  title: string;
  description: string;
  producer_name: string;
  default_unit_id: number;
  labels: IProductLabel[];
  technical_attributes: ITechnicalAttribute[];
  url_link: string;
}

export interface ICategoryListItem {
  id: number;
  name: string;
  products_count: number;
  subcategories: ICategoryListItem[];
  subcategories_total_count: number;
  url_link: string;
}

export interface IStatus {
  color: string;
  icon: string;
  label: string;
}

export interface IExportRequest {
  name: string;
  type: string;
  periodic: boolean;
  period: number;
  start_date: string;
  category_id?: number;
  attributes: {
    id: number;
    chosen: boolean;
    field_name: string;
  }[];
  send_on_mail: boolean;
  csv_separator: string;
}
