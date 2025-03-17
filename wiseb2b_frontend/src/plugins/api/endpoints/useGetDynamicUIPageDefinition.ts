// Hook odpowiedzialny za pobranie listy koszyków

import axios from 'api/axios';
import { useQuery, UseQueryOptions } from 'react-query';
import { ICommandResponseError as IError } from 'api/types';

export type ITranslation = { language: string; translation: string };

export type IDictionaryValue = {
  value: string | number | ITranslation | null;
  text: string;
};

export type IFilter = {
  type: 'search';
  label: string;
  field_symbol: string;
  default_value?: string;
  dictionary?: {
    values: IDictionaryValue[];
    multi_choice: boolean;
    separator_multi_choice: string;
    values_get_u_i_api: null | string;
  };
};

export type IField = {
  type: string;
  value: string | ITranslation[];
  label: string;
  editable: true;
  dictionary: {
    values: IDictionaryValue[];
    multi_choice: boolean;
    separator_multi_choice: string;
    values_get_u_i_api: null | string;
  };
  symbol: string;
  field_symbol: string;
  width: number;
  height: number;
  nullable?: boolean;
  hint?: string;
};

export type IColumn = {
  type: 'text' | 'address' | 'boolean';
  label: string;
  field_symbol: string;
  align?: 'left' | 'right' | 'center';
  context_action?: 'DISPLAY_ENTITY';
};

export type IAction = {
  symbol: string;
  type: 'CONTEXT_MAIN' | 'CONTEXT_ADDITIONAL' | 'NO_CONTEXT' | 'CONTEXT_MULTICHOICE' | 'COLUMN';
  label: string;
  style?: 'ACTION_EDIT' | 'DELETE' | 'ACTION_PLUS';
  method:
    | 'CallCommand'
    | 'Cancel'
    | 'Redirect'
    | 'OpenModalComponent'
    | 'Delete'
    | 'OpenModalDynamicPage'
    | 'Add'
    | 'Update'
    | 'RefreshComponent';
  column_field_symbol?: string;
  method_params?: {
    command_url: string;
    params_list: string;
    page?: string;
    dynamic_page?: string;
    component_name?: string;
    page_url?: string;
    result_params_to_save?: string;
  };
  sub_components_to_refresh?: string;
  refresh_data_mode?: 'ONSUCCESS';
};

export type ILoadDataParam = {
  get_url: string;
  params_list: string | null;
};

export type IName = {
  name: string;
  component_symbol: string;
};

export type IComponent = {
  filters: IFilter[];
  fields: IField[];
  actions: IAction[];
  type: string;
  symbol: string;
  label: string;
  columns: IColumn[];
  load_data_params?: ILoadDataParam;
  params_list?: string;
  names?: IName[];
  components?: IComponent[];
};

// typ zwracanych danych
export type IResponse = {
  page_definition: {
    page_symbol: string;
    components: IComponent[];
    layout: 'PAGE';
    title: string;
  };
  message?: string;
  message_style?: string;
  show_message?: boolean;
  status?: number;
  table_prefix: string;
};

const getDynamicUIPageDefinition = (pageSymbol: string, params?: object): Promise<IResponse> =>
  axios.get(`/dynamicUI/page_definition/${pageSymbol}`, { params });

export const useGetDynamicUIPageDefinition = (
  pageSymbol: string,
  params?: object,
  options?: UseQueryOptions<IResponse, IError>
) =>
  useQuery<IResponse, IError>(
    ['dynamic-ui-page-definition', pageSymbol, params],
    () => getDynamicUIPageDefinition(pageSymbol, params),
    options
  );
