import React, { FC, useState, useEffect, Dispatch, SetStateAction, ChangeEvent } from 'react';

import { reduxActions, useDispatch } from 'store';
import { IDynamicUiField } from 'plugins/api/types';

import { FormElement } from 'components/controls';

// typ danych wejściowych
interface IProps {
  field: IDynamicUiField;
  pageSymbol: string;
  componentSymbol: string;
}

const DynamicDecimal: FC<IProps> = ({ field, pageSymbol, componentSymbol }) => {
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
        fieldValue: parseFloat(e.target.value)
      })
    );

    setValue(e.target.value);
  };

  return (
    <div className="dynamicDecimal">
      <FormElement>
        <label>{field.label}</label>
        <input
          type="number"
          value={value}
          onChange={(e) => onChangeHandler(e)}
          disabled={!field.editable}
        />
        {field.hint && <small>{field.hint}</small>}
      </FormElement>
    </div>
  );
};

export default DynamicDecimal;
