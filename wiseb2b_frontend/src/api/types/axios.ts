// składnik standardowego błędu - error_field
export interface ICommandResponseErrorField {
  message: string;
  property_path: string;
  invalid_value?: string;
}

interface IFieldInfo {
  invalid_value: string;
  message: string;
  message_style: string;
  property_path: string;
  property_name: string;
}

// standardowy typ błędu
export interface ICommandResponseError {
  fields_info: IFieldInfo[];
  message: string;
  show_message: boolean;
  show_modal: boolean;
  status: 0; // 0 - error, 1 - success
  error_fields?: ICommandResponseErrorField[];
}

// standardowy typ poprawnęj komendy
export interface ICommandResponseSuccess {
  message: string;
  show_message: boolean;
  status: 1; // 0 - error, 1 - success
  data?: Record<string, unknown> | unknown[];
}
