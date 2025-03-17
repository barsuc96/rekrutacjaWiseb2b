import React, { FC, useState, useEffect, ChangeEvent } from 'react';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import { FormElement, Input } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicText: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
  const dispatch = useDispatch();

  // ustawianie wartości
  const [value, setValue] = useState<string>((field.value as string) || '');

  // ustawienie value przy zmianie danych
  useEffect(() => {
    setValue(field.value as string);
  }, [field.value]);

  const onChangeHandler = (e: ChangeEvent<HTMLInputElement>) => {
    dispatch(
      reduxActions.setFieldValue({
        pageSymbol,
        componentSymbol,
        fieldName: field.field_symbol,
        fieldValue: e.target.value
      })
    );

    setValue(e.target.value);
  };

  return (
    <div className="dynamicText">
      <FormElement>
        <label>{field.label}</label>
        <input value={value} onChange={(e) => onChangeHandler(e)} disabled={!field.editable} />
        {field.hint && <small>{field.hint}</small>}
      </FormElement>
    </div>
  );
};

export default DynamicText;
