import React, { FC, useState, useEffect } from 'react';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import { FormElement, DatePicker } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicDate: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();

  // ustawianie wartości
  const [value, setValue] = useState<string>((field.value as string) || '');

  // ustawienie value przy zmianie danych
  useEffect(() => {
    setValue(field.value as string);
  }, [field.value]);

  const onChangeHandler = (date: string | null) => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: date
      })
    );

    setValue(date || '');
  };

  return (
    <div className="dynamicDate">
      <FormElement>
        <label>{field.label}</label>
        <div className={field.nullable ? 'dynamicDateClearable' : ''}>
          <DatePicker
            onChange={(e) => onChangeHandler(e)}
            date={value}
            clearable={field.nullable && !!value}
          />
        </div>
      </FormElement>
    </div>
  );
};

export default DynamicDate;
